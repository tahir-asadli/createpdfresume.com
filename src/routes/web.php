<?php

use App\Http\Controllers\BuilderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EpointController;
use App\Http\Controllers\ImportResume;
use App\Http\Controllers\PayriffController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UnsubscribeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ViewController;
use App\Http\Middleware\AppMiddleware;
use App\Jobs\CreatePDF;
use App\Jobs\GenerateTransparentProfileImage;
use App\Mail\CrashNotification;
use App\Mail\CustomerEmail;
use App\Models\Block;
use App\Models\Page;
use App\Models\Resume;
use App\Models\Template;
use App\Models\Tracking;
use App\Models\View;
use App\Models\Widget;
use App\Services\ResumeImporter;
use App\Services\SubscriptionCronJob;
use App\Services\ViewsCronJob;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Rct567\DomQuery\DomQuery;

// Route::get('job', function () {
//     GenerateTransparentProfileImage::dispatch(auth()->user()->profile);
// });

// Route::get('ai', function () {
//     if (auth()->check() && auth()->user()->isAdmin()) {


//         // $pdfPath = public_path('/example-resumes/resume1.pdf');
//         // $pdfPath = public_path('/example-resumes/resume2.pdf');
//         $pdfPath = public_path('/example-resumes/resume7.pdf');
//         // $pdfPath = public_path('/example-resumes/resume4.pdf');
//         // $pdfPath = public_path('/example-resumes/resume4.pdf');
//         // $pdfPath = public_path('/example-resumes/business_resume25.pdf');
//         // $pdfPath = public_path('/example-resumes/cv-template.pdf');
//         // $pdfPath = public_path('/example-resumes/education_resume25.pdf');
//         // $pdfPath = public_path('/example-resumes/government_leadership_resume25.pdf');
//         // $pdfPath = public_path('/example-resumes/resume7.pdf');
//         $resumeImporter = new ResumeImporter($pdfPath, auth()->user());

//         $resumeImporter->import();

//         if ($resumeImporter->isSuccessful()) {
//             dd('true');
//         } else {
//             dd('false', $resumeImporter->getError());
//         }

//         dd($resumeImporter->getError());
//     }
//     return null;
// });


Route::post('ping', function (Request $request) {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return '0';
    }
    if (!empty($_SERVER['HTTP_X_UUID_TOKEN'])) {
        $token = $_SERVER['HTTP_X_UUID_TOKEN'];
        $views = View::where('uuid', $token)->get();
        if ($views && isset($views[0])) {
            $view = $views[0];
            $createdAt = $view->created_at;
            $updatedAt = now();
            if (auth()->check()) {
                $view->uid = auth()->user()->id;
            }
            $secondsDifference = (int) $createdAt->diffInSeconds($updatedAt);
            $view->duration = $secondsDifference;
            $view->save();
            return '1';
        } else {
            return '3';
        }
    }
    return '2';
});



// Route::get('cron', function () {
//     ViewsCronJob::run();
//     return 'a';
// });

// Route::get('saveavatar', function () {
//     // if (auth()->check()) {
//     //     auth()->user()->addProfileImage('');
//     // }
//     // dd(auth()->user()->hasSubscription());
//     // SubscriptionCronJob::run();
// });

Route::get('dw', function () {
    if (auth()->check()) {
        $remaining = RateLimiter::remaining(config('site.download_all_rate_limiter_key') . auth()->user()->id, config('site.download_all_limit'));
        if ($remaining > 0) {
            $userId = auth()->user()->id;
            $tempDir = sys_get_temp_dir();
            $files = [];
            if ($tempDir) {
                $resumes = auth()->user()->resumes;
                foreach ($resumes as $resume) {
                    $styles = $resume->template->styleNames();
                    $templates_path = $resume->getTemplatesFolder();
                    $pages = $resume->getPages();
                    foreach ($pages as $page) {
                        $page_html_file_name = $userId . '-' . $resume->id . '-' . $page . '.html';
                        $page_pdf_file_name = $userId . '-' . $resume->id . '.pdf';
                        $page_html_file_path = $templates_path . $page_html_file_name;
                        $page_pdf_file_path = $templates_path . $page_pdf_file_name;
                        if (is_file($page_html_file_path)) {
                            $files[$page_html_file_name] = $page_html_file_path;
                        }
                        if (is_file($page_pdf_file_path)) {
                            $files[$page_pdf_file_name] = $page_pdf_file_path;
                        }
                        foreach ($styles as $style) {
                            $page_html_file_name_style = $userId . '-' . $resume->id . '-' . $page . '-' . $style . '.html';
                            $page_pdf_file_name_style = $userId . '-' . $resume->id . '-' . $style . '.pdf';
                            $page_pdf_file_name_page_style = $userId . '-' . $resume->id . '-' . $page . '-' . $style . '.pdf';
                            $page_html_file_path_style = $templates_path . $page_html_file_name_style;
                            $page_pdf_file_path_style = $templates_path . $page_pdf_file_name_style;
                            $page_pdf_file_name_page_path_style = $templates_path . $page_pdf_file_name_page_style;
                            if (is_file($page_html_file_path_style)) {
                                $files[$page_html_file_name_style] = $page_html_file_path_style;
                            }
                            if (is_file($page_pdf_file_path_style)) {
                                $files[$page_pdf_file_name_style] = $page_pdf_file_path_style;
                            }
                            if (is_file($page_pdf_file_name_page_path_style)) {
                                $files[$page_pdf_file_name_page_style] = $page_pdf_file_name_page_path_style;
                            }
                        }
                    }
                }
                if (count($files) > 0) {

                    RateLimiter::attempt(
                        config('site.download_all_rate_limiter_key') . auth()->user()->id,
                        config('site.download_all_limit'),
                        function () {
                        },
                        86400
                    );
                    $zip = new ZipArchive();
                    $filename = $tempDir . "/user-files-" . $userId . ".zip";
                    if ($zip->open($filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
                        return 'cant  create zip file';
                    } else {
                        try {
                            foreach ($files as $k => $v) {
                                if (is_file($v)) {
                                    $zip->addFromString($k, file_get_contents($v));
                                }
                            }
                            $zip->close();
                            header('Content-Type: application/zip');
                            header('Content-disposition: attachment; filename=' . 'Your files');
                            header('Content-Length: ' . filesize($filename));
                            readfile($filename);
                        } catch (Exception $e) {
                            info($e->getMessage());
                            return 'Error occured';
                        }
                    }
                } else {
                    return 'no files found';
                }
            } else {
                return 'server error';
            }
        } else {
            return 'You reached your download limit today!';
        }
    } else {
        return 'Forbidden';
    }
})->name('download-all');

Route::pattern('locale', 'en|az|tr|ru|es');

Route::prefix('/{locale?}')->group(function () {

    Route::get('/', function () {
        return 'change';
        return view('new.index');
    })->name('home');

    Route::get('/success', function () {
        return view('new.success');
    })->name('success');

    Route::get('/error', function () {
        return view('new.error');
    })->middleware(['verified'])->name('error');

    Route::get('privacy-policy', function () {
        return view('new.privacy-policy');
    })->name('privacy-policy');

    Route::get('contact', function () {
        return view('new.contact');
    })->name('contact');

    Route::get('about', function ($locale = null) {
        return view('new.about');
    })->name('about');
});


// Route::get('verify-notice', function () {
//     if (auth()->check()) {
//         dd('ok');
//         if (auth()->user()->hasVerifiedEmail()) {
//             dd('ok');
//         } else {
//             dd('nok');
//         }
//     } else {
//         dd('nok');
//     }
//     return 'aa';
//     return view('verification.notice');
// })->middleware([AppMiddleware::class])->name('verification.notice');

Route::get('/', function () {
    return view('index');
});

Route::get('/success', function () {
    return view('success');
});

Route::get('/error', function () {
    return view('error');
})->middleware(['verified']);

Route::get('privacy-policy', function () {
    return view('new.privacy-policy');
});

Route::get('contact', function () {
    return view('new.contact');
});

Route::get('about', function ($locale = null) {
    return view('new.about');
});

Route::get('verify-notice', function () {
    if (auth()->check()) {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
    }
    return view('verification.notice');
})->middleware([AppMiddleware::class])->name('verification.notice');
;

// Route::get('/', function () {
//     return view('index');
// })->name('home');

// Route::get('/success', function () {
//     return view('success');
// })->name('success');


// Route::get('/error', function () {
//     return view('error');
// })->middleware(['verified'])->name('error');

// Route::get('privacy-policy', function () {
//     return view('privacy-policy');
// });
// Route::get('contact', function () {
//     return view('contact');
// });

// Route::get('about', function () {
//     return view('about');
// });

Route::post('/result', EpointController::class)->name('result');
// Route::any('/successcb', PayriffController::class)->name('payriff_result');




Route::get('/unsubscribe/{customer:token}', UnsubscribeController::class)->name('unsubscribe');

Route::middleware([AppMiddleware::class, 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {

        Route::post('pdfgen', function () {
            if (!request('resumeUUID')) {
                return ['status' => 'error', 'message' => __('Please select a resume!')];
            }
            $resume = auth()->user()->resumes()->where('uuid', request('resumeUUID'))->first();
            if (!$resume) {
                return ['status' => 'error', 'message' => __('Resume not found!')];
            }
            $trackingId = Str::uuid();
            $tracking = Tracking::create(
                [
                    'uuid' => $trackingId,
                    'status' => 'pending'
                ]
            );
            if ($tracking) {
                $style = $resume->style;
                CreatePDF::dispatch($resume, $style, $trackingId);

                $userId = auth()->user()->id;
                $limit = auth()->user()->PDFGenerationLimit();
                $key = 'generate' . $userId;
                $remaining = RateLimiter::remaining($key, $limit);
                if ($remaining > 0) {
                    RateLimiter::increment($key);
                    return ['status' => 'ok', 'trackingId' => $trackingId];
                } else {
                    return ['status' => 'error', 'message' => __('Daily limit reached!')];
                }
            } else {
                return ['status' => 'error', 'message' => __('Server error!')];
            }
        });

        Route::post('pdfgenping', function () {
            if (request('trackingId')) {
                $tracking = Tracking::where('uuid', request('trackingId'))->first();
                if (!$tracking) {
                    return ['status' => 'error', 'message' => __('Server error: #1')];
                }
                if ($tracking->status == 'completed') {
                    try {
                        $data = unserialize($tracking->data);
                        return ['status' => 'completed', 'download_url' => $data['download_url']];
                    } catch (\Throwable $th) {
                        return ['status' => 'error', 'message' => __('Server error: #2')];
                    }
                } else {

                }
            } else {
            }
            return ['status' => 'pending'];
        });
        Route::post('importResume', ImportResume::class)->middleware('throttle:300,1440')->name('import-resume');
        Route::get('import', function () {
            return view('dashboard.import');
        })->name('import');

        Route::get('templates', function () {
            $templates = Template::active()->orderBy('created_at', 'desc')->get();
            // $templates = Template::availableTemplates();
            return view('dashboard.templates', compact('templates'));
        })->name('templates');

        Route::get('resumes', function () {
            return view('dashboard.resumes');
        })->name('resumes');

        Route::get('builder/{resume:uuid}/{page}', BuilderController::class)->name('builder');
        Route::get('new-builder/{resume:uuid}/{page}', [BuilderController::class, 'new'])->whereNumber('page')->name('new-builder');
        Route::post('new-builder/render/{resume:uuid}/{page}', [BuilderController::class, 'renderTemplate'])->name('new-builder-render');
        Route::post('switch-resume-style', function () {
            $resumeUUID = request()->post('resumeUUID');
            $styleSlug = request()->post('styleSlug');
            if (empty($resumeUUID))
                return [
                    'status' => 'error',
                    'message' => __('Unauthorized action')
                ];
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return [
                    'status' => 'error',
                    'message' => __('Resume not found')
                ];
            $styles = explode(',', $resume->template->styles);
            if ($styleSlug == 'default') {
                $resume->style = 'default';
                $resume->save();

            } else {
                if (in_array($styleSlug, $styles)) {
                    $resume->style = $styleSlug;
                    $resume->save();
                } else {
                    return [
                        'status' => 'error',
                        'message' => __('Resume style not found')
                    ];
                }
            }
            return ['status' => 'success', 'message' => 'Resume style updated'];
        })->name('switch-resume-style');
        Route::post('add-new-block', function () {
            $resumeUUID = request()->post('resumeUUID');
            $sectionSlug = request()->post('sectionSlug');
            $widgetName = request()->post('widgetName');
            $page = request()->post('page') ? (int) request()->post('page') : 1;
            if (empty($resumeUUID))
                return [
                    'status' => 'error',
                    'message' => __('Unauthorized action')
                ];
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return [
                    'status' => 'error',
                    'message' => __('Resume not found')
                ];
            $template = $resume->template;
            $html = $template->html($page);
            $dom = new DomQuery($html);
            $template->setDOM($dom);
            $template->getWidgetTemplates();
            $section = $template->sections()->where('section', $sectionSlug)->where('active', true)->first();
            if (!$section) {
                return [
                    'status' => 'error',
                    'message' => __('Section not found')
                ];
            }
            $widget = Widget::where('name', $widgetName)->where('active', true)->first();
            if (!$widget) {
                return [
                    'status' => 'error',
                    'message' => __('Widget not found')
                ];
            }
            $block = Block::create([
                'widget_id' => $widget->id,
                'resume_id' => $resume->id,
                'resume_page' => $page,
                'section' => $section->section,
                'active' => true,
            ]);
            if (!$block) {
                return [
                    'status' => 'error',
                    'message' => __('Couldn\'t create  the block')
                ];
            }
            $template->getWidgetTemplates();
            $html = $template->renderBlock($block, auth()->user(), true);
            return ['status' => 'success', 'message' => 'Block added', 'blockHTML' => $html];
        })->name('add-new-block');
        Route::post('block-settings', function () {
            $resumeUUID = request()->post('resumeUUID');
            $blockId = request()->post('blockId');
            if (empty($resumeUUID))
                return __('Invalid resume ID');
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return __('Resume not found');
            if (empty($blockId))
                return __('Invalid block ID');
            $blockId = request()->post('blockId');
            $block = $resume->blocks()->where("id", $blockId)->first();
            if (!$block) {
                return __('Block not found');
            }
            $template = $resume->template;
            $dateExample = Carbon::now();
            $blendModes = $block->blendModeGroups();
            return response()->json([
                'html' => view('dashboard.new-block-settings', compact('block', 'dateExample', 'blendModes', 'template'))->render(),
                'message' => 'View loaded successfully!',
                'status' => 'success'
            ]);
        })->name('block-settings');
        Route::post('update-block-settings', function (Request $request) {
            $resumeUUID = request()->post('resumeUUID');
            if (empty($resumeUUID))
                return [
                    'status' => 'error',
                    'message' => __('Invalid resume ID')
                ];
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return [
                    'status' => 'error',
                    'message' => __('Resume not found')
                ];
            $formFields = request()->post('form');
            $blockId = $formFields['blockId'];
            if (empty($blockId))
                return [
                    'status' => 'error',
                    'message' => __('Invalid block ID')
                ];

            $block = $resume->blocks()->where("id", $blockId)->first();
            if (!$block) {
                return [
                    'status' => 'error',
                    'message' => __('Block not found')
                ];
            }
            $formFields['from'] = !empty($formFields['from']) ? (int) $formFields['from'] : null;
            $formFields['to'] = !empty($formFields['to']) ? (int) $formFields['to'] : null;
            $formFields['active'] = !empty($formFields['active']) ? (bool) $formFields['active'] : false;
            $formFields['height'] = !empty($formFields['height']) ? (int) $formFields['height'] : 1;
            $formFields['bold'] = !empty($formFields['bold']) ? (bool) $formFields['bold'] : false;
            $formFields['italic'] = !empty($formFields['italic']) ? (bool) $formFields['italic'] : false;
            $formFields['underline'] = !empty($formFields['underline']) ? (bool) $formFields['underline'] : false;
            $formFields['strikethrough'] = !empty($formFields['strikethrough']) ? (bool) $formFields['strikethrough'] : false;
            $formFields['color'] = !empty($formFields['color']) ? (string) $formFields['color'] : null;
            // $formFields['radius'] = (int) $formFields['radius'];
            $formFields['filter'] = !empty($formFields['filter']) ? (string) $formFields['filter'] : null;
            $formFields['align'] = !empty($formFields['align']) ? (string) $formFields['align'] : null;
            $formFields['uppercase'] = !empty($formFields['uppercase']) ? (bool) $formFields['uppercase'] : false;
            $formFields['blend'] = !empty($formFields['blend']) ? (string) $formFields['blend'] : null;
            // $formFields['title'] = !empty($formFields['title']) ? (string) $formFields['title'] : null;
            $validator = Validator::make(
                $formFields,
                array(
                    'title' => 'max:1024',
                    'from' => 'numeric|nullable|max:100',
                    'to' => 'numeric|nullable|max:100',
                    'active' => 'required|boolean',
                    'height' => 'numeric|nullable|max:100',
                    'bold' => 'required|boolean',
                    'italic' => 'required|boolean',
                    'underline' => 'required|boolean',
                    'strikethrough' => 'required|boolean',
                    'uppercase' => 'required|boolean',
                    'color' => 'nullable|hex_color|max:64',
                    'radius' => 'numeric|nullable|max:9999',
                    'filter' => 'nullable|max:64',
                    'align' => 'nullable|max:64',
                    'blend' => 'nullable|max:64',
                    'dateformat' => 'nullable|max:64',
                )
            );
            $errors = $validator->errors();
            if (count($errors)) {
                return ['status' => 'error', 'errors' => $errors];
            }
            $validatedData = $validator->getData();
            $updatedData = [];
            if (!empty($validatedData['title'])) {
                $updatedData['title'] = $validatedData['title'];
            } else {
                $updatedData['title'] = null;
            }
            if (isset($_POST['form'])) {
                if ($_POST['title'] == ' ' || $_POST['title'] == 'empty') {
                    $updatedData['title'] = ' ';
                }
            }
            if (!empty($validatedData['height'])) {
                $updatedData['height'] = $validatedData['height'];
            }
            if (!empty($validatedData['from'])) {
                $updatedData['from'] = (int) $validatedData['from'] == 0 ? null : (int) $validatedData['from'];
            } else {
                $updatedData['from'] = null;
            }
            if (!empty($validatedData['to'])) {
                $updatedData['to'] = (int) $validatedData['to'] == 0 ? null : (int) $validatedData['to'];
            } else {
                $updatedData['to'] = null;
            }
            $updatedData['bold'] = !empty($validatedData['bold']) ? (bool) $validatedData['bold'] : false;
            $updatedData['italic'] = !empty($validatedData['italic']) ? (bool) $validatedData['italic'] : false;
            $updatedData['underline'] = !empty($validatedData['underline']) ? (bool) $validatedData['underline'] : false;
            $updatedData['strikethrough'] = !empty($validatedData['strikethrough']) ? (bool) $validatedData['strikethrough'] : false;
            $updatedData['uppercase'] = !empty($validatedData['uppercase']) ? (bool) $validatedData['uppercase'] : false;
            // if (!empty($validatedData['color'])) {
            $updatedData['color'] = $validatedData['color'];
            // }
            if (isset($formFields['radius']) && in_array($validatedData['radius'], [0, 5, 9999])) {
                $updatedData['radius'] = (int) $validatedData['radius'];
            }

            if (
                !empty($validatedData['filter']) &&
                in_array($validatedData['filter'], $block->effects())
            ) {
                $updatedData['filter'] = (string) $validatedData['filter'];
            } else {
                $updatedData['filter'] = null;
            }

            if (isset($formFields['transparent'])) {
                $transparent = $formFields['transparent'] == 'yes';
                $block->resume->user->profile->transparent = $transparent;
                $block->resume->user->profile->save();
            }

            if (
                !empty($validatedData['blend']) &&
                in_array($validatedData['blend'], $block->blendModes())
            ) {
                $updatedData['blend'] = (string) $validatedData['blend'];
            } else {
                $updatedData['blend'] = null;
            }

            if (
                !empty($validatedData['align']) &&
                in_array($validatedData['align'], ['left', 'center', 'right'])
            ) {
                $updatedData['align'] = (string) $validatedData['align'];
            } else {
                $updatedData['align'] = null;
            }

            if (
                !empty($validatedData['dateformat']) &&
                in_array($validatedData['dateformat'], [
                    'year',
                    'month',
                    'day',
                    'monthname',
                    'daymonthname'
                ])
            ) {
                $updatedData['dateformat'] = (string) $validatedData['dateformat'];
            } else {
                $updatedData['dateformat'] = 'year';
            }

            $updatedData['active'] = !empty($validatedData['active']) ? $validatedData['active'] == "1" : false;
            $block->update($updatedData);
            return ['status' => 'success', $formFields, $validatedData, $updatedData];
        })->name('update-block-settings');
        Route::post('delete-block', function () {
            $resumeUUID = request()->post('resumeUUID');
            $blockId = request()->post('blockId');

            if (empty($resumeUUID))
                return [
                    'status' => 'error',
                    'message' => __('Unauthorized action')
                ];
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return [
                    'status' => 'error',
                    'message' => __('Resume not found')
                ];
            $block = $resume->blocks()->where("id", $blockId);
            if (!$block) {
                return [
                    'status' => 'error',
                    'message' => __('Block not found')
                ];
            }
            $block->delete();
            return ['status' => 'success', 'message' => 'Blocks deleted!'];
        })->name('delete-block');
        Route::post('sortBlocks', function () {
            $resumeUUID = request()->post('resumeUUID');
            $page = request()->post('page');
            if (empty($resumeUUID))
                return [
                    'status' => 'error',
                    'message' => __('Unauthorized action')
                ];
            $resume = auth()->user()->resumes()->where('uuid', $resumeUUID)->first();
            if (!$resume)
                return [
                    'status' => 'error',
                    'message' => __('Resume not found')
                ];
            $template = $resume->template;
            $templateSections = $template->sections->map(function ($section) {
                return $section->section->value;
            });
            $sections = request()->post('sections');
            $widgets = Widget::where('active', true)->get()->map(function ($widget) {
                return $widget->name;
            });
            $blockIds = $resume->blocks()->where('resume_page', $page)->get()->map(function ($block) {
                return $block->id;
            });
            // dd($blockIds, request()->post());
            collect($sections)->map(function ($section, $sectionSlug) use ($templateSections, $widgets, $blockIds) {
                if (in_array($sectionSlug, $templateSections->toArray())) {
                    foreach ($section as $order => $block) {
                        $order = (int) $order + 1;
                        $blockId = $block['id'];
                        $widgetName = $block['widgetName'];
                        if (in_array($widgetName, $widgets->toArray()) && in_array($blockId, $blockIds->toArray())) {
                            $blockRecord = Block::find($blockId);
                            if ($blockRecord) {
                                $blockRecord->update([
                                    'order' => $order,
                                    'section' => $sectionSlug
                                ]);
                            } else {
                            }
                        } else {
                            dump('error:' . $widgetName);
                        }
                    }
                }
            });
            return ['status' => 'success', 'message' => 'Blocks updated!'];
        })->name('sort-blocks');

        Route::get('/preview/{resume:uuid}/{page}', PreviewController::class)->name('preview');
        Route::get('/view/{resume:uuid}', ViewController::class)->name('view');
        Route::get('/download/{resume:uuid}/{style}', DownloadController::class)->name('download');

        Route::get('subscription', function () {
            return view('dashboard.subscription');
        })->name('subscription');

        Route::get('billing', function () {
            return view('dashboard.billing');
        })->name('billing');

        Route::get('/', function () {
            $templates = Template::active()->orderBy('plan_id', 'asc')->get();
            return view('dashboard.templates', compact('templates'));
        })->name('dashboard');


        Route::get('/profile', function () {
            return view('dashboard.profile');
        })->name('profile');

        Route::get('/informations', function () {
            return view('dashboard.informations');
        })->name('informations');

        Route::get('/skills', function () {
            return view('dashboard.skills');
        })->name('skills');

        Route::get('/languages', function () {
            return view('dashboard.languages');
        })->name('languages');

        Route::get('/experiences', function () {
            return view('dashboard.experiences');
        })->name('experiences');

        Route::get('/educations', function () {
            return view('dashboard.educations');
        })->name('educations');

        Route::get('/socials', function () {
            return view('dashboard.socials');
        })->name('socials');

        Route::get('/projects', function () {
            return view('dashboard.projects');
        })->name('projects');

        Route::get('/awards', function () {
            return view('dashboard.awards');
        })->name('awards');

        Route::get('/certificates', function () {
            return view('dashboard.certifications');
        })->name('certificates');

        Route::get('/hobbies', function () {
            return view('dashboard.hobbies');
        })->name('hobbies');

        Route::get('/references', function () {
            return view('dashboard.references');
        })->name('references');

        Route::get('cancel', function () {
            return view('dashboard.cancel');
        })->name('cancel');


        Route::get('checkout/{plan:slug}', CheckoutController::class)->name('checkout');


        Route::post('enableRenew', [SubscriptionController::class, 'enableRenew'])->name('enableRenew');
        Route::post('disableRenew', [SubscriptionController::class, 'disableRenew'])->name('disableRenew');
        Route::get('dbck', function () {
            $fileName = request('dbckfn');
            $sanitaziedFileName = pathinfo($fileName, PATHINFO_BASENAME);
            $fullFilePath = Storage::disk('local')->path("backups/{$sanitaziedFileName}");
            if (!Str::endsWith($sanitaziedFileName, '.tar.gz')) {
                abort(403, 'Unauthorized access');
            }
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized access');
            }
            // Check if the file exists before attempting to download
            if (!file_exists($fullFilePath)) {
                abort(404, 'File not found');
            }

            return response()->download($fullFilePath, $sanitaziedFileName);
        })->name('backup-download');

    });

});


Route::post('/email/verification-notification', function (Request $request) {
    auth()->user()->sendEmailVerificationNotification();
    return back()->with('message', __('Email sent!'));
})->middleware([AppMiddleware::class, 'throttle:6,1'])->name('verification.send');



Route::get('verification/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect(route('dashboard'));
})->middleware([AppMiddleware::class])->name('verification.verify');


Route::get('sitemap.xml', function () {
    $pages = Page::siteMapPages();
    return response()->view('sitemap', compact('pages'))->header('Content-Type', 'text/xml');
});
Route::get('google', [SocialController::class, 'google'])->name('google');
Route::get('/auth/callback/google', [SocialController::class, 'google_callback']);
Route::get('github', [SocialController::class, 'github'])->name('github');
Route::get('/auth/callback/github', [SocialController::class, 'github_callback']);
Route::get('linkedin', [SocialController::class, 'linkedin'])->name('linkedin');
Route::get('/auth/callback/linkedin', [SocialController::class, 'linkedin_callback']);

// Route::post('/upload', UploadController::class)->name('upload-post');
/*


nohup php artisan schedule:work > ./storage/logs/schedule.log 2>&1 &
nohup php artisan queue:work > ./storage/logs/queue.log 2>&1 &


kill nohup
ps aux | grep "php artisan schedule:work"
ps aux | grep "php artisan"
kill -9 12345
pkill -f "php artisan schedule:work"
pkill -f "php artisan "

*/

/*
'operation_code' => '001', add cart
001- kart qeydiyyatı
100- istifadəçi ödənişi
fullname
occupation
slogan
profile_image
about


work_experiences
    company
    position
    about
    location
    start_date
    end_date
educations
    school
    degree
    field
    location
    description
    start_date
    end_date
skills %
    name
    level
    user_id
socials
    name

projects
    name
    description
    url
awards
    name
    description
    organization
    date
languages %
    name
    level
    certification
certifications
    name
    organization
    date
    description

hobbies
    name
contacts
    web
    phone
    email
    address
references
        name
        position
        company
        email
        phone

follow me

Fullname
Job Title
Slogan
About
Profile image
Phone
Email
Web
Address
*/

// https://preline.co/docs/advanced-range-slider.html#pass-values-to-inputsurl

/*
pa make:model Resume -m
pa livewire:make AddResume
pa livewire:make EditResume
pa livewire:form CreateResumeForm
pa livewire:form EditResumeForm
pa livewire:make Resumes
pa make:policy ResumePolicy --model=Resume
*/

/*
pa make:filament-resource Plan --generate
poc
pac
pa make:policy BlockPolicy --model=Block
pa make:filament-resource Subscription --generate
pa make:filament-resource Transaction --generate
pa make:filament-resource Order --generate
pa make:filament-resource Card --generate
*/

/*

space
h1
h2
h3
h4
h5
h6
h7

*/


/*

work experience date present
birthday widget
datepicker
*/


/*

header
    fullname
    job_title
    slogan


content
    experiences
    educations
    certifications
    hobbies
    projects
    about

sidebar
    image
    awards
    skills
    languages
    socials
    references
    contacts
    address

footer
    socials
     return collect(explode(PHP_EOL, $this->features))->filter(fn($feature) => trim($feature) != '');
    */


/*
.widget.skills {}
.widget.languages {}
.widget.experiences {}
.widget.educations {}
.widget.socials {}
.widget.projects {}
.widget.awards {}
.widget.certifications {}
.widget.hobbies {}
.widget.references {}
.widget.fullname {}
.widget.job {}
.widget.slogan {}
.widget.about {}
.widget.contacts {}
.widget.phone {}
.widget.email {}
.widget.web {}
.widget.address {}
.widget.image {}
.widget.h1 {}
.widget.h2 {}
.widget.h3 {}
.widget.h4 {}
.widget.h5 {}
.widget.h6 {}
.widget.p {}
.widget.space {}
.widget.ol {}
.widget.ul {}

    https://app.mailersend.com/domains/ywj2lpno7dkg7oqz
    search for dd and dumps
    .page height
    //     $query->where('user_id', auth()->id());
*/

// https://creativemarket.com/reuixstudio/285144375-ResumeCV?cjdata=MXxOfDB8WXww&cjevent=1a5910dbe83311ef83bf00270a18b8f9&utm_source=cj&utm_content=100857967&utm_term=15168186&utm_medium=affiliate&utm_campaign=6481472#fullscreen
// https://www.behance.net/gallery/213715763/Minimal-Resume-Template?tracking_source=search_projects|psd+resume&l=1
// https://www.behance.net/gallery/135563355/RESUMECV?tracking_source=search_projects|psd+resume&l=27
// https://blog.uxfol.io/product-designer-resume/

// pa make:filament-resource Blacklist --generate


// Sarah
// Press
// Code
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Process;
use Rct567\DomQuery\DomQuery;

class Resume extends Model
{
    protected $templates_folder = 'app/private/user-templates/';
    protected $download_templates_folder = '/user-templates/';

    public function getTemplatesFolder()
    {
        return storage_path($this->templates_folder);
    }

    public function scopeActive($query)
    {
        $subscription = $this->user->subscription()->active()->first();
        if (!$subscription) {
            return $query->where('template_id', 99999999);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getPreviewURL($page = 1)
    {
        return route('preview', [$this->uuid, (int) $page]);
    }

    public function getViewURL($page = 1)
    {
        return route('view', [$this->uuid, (int) $page]);
    }


    public function getPages()
    {

        return array_unique($this->blocks()->get()->pluck('resume_page')->map(function ($i) {
            return $i;
        })->toArray());
    }


    public function generatePDF($style = false)
    {
        $templates_path = storage_path($this->templates_folder);
        $pages = $this->getPages();
        $output_pdf = $templates_path . $this->user->id . '-' . $this->id . '-' . $style . '.pdf';
        // chrome-gnome-shell --headless --disable-gpu --print-to-pdf ./resume.html
        if (file_exists($output_pdf)) {
            unlink($output_pdf);
        }
        $pages_pdf_files = '';
        foreach ($pages as $page) {
            $page_html_content = $this->template->renderResume($this, $page, 'pdf', $style);
            $page_html_file_name = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.html';
            $page_pdf_file_name = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.pdf';
            $page_html_file_path = $templates_path . $page_html_file_name;
            $page_pdf_file_path = $templates_path . $page_pdf_file_name;
            if (file_exists($page_html_file_path)) {
                unlink($page_html_file_path);
            }
            file_put_contents($page_html_file_path, $page_html_content);
            $command = "google-chrome --no-sandbox --headless --disable-gpu --print-to-pdf=\"$page_pdf_file_path\" $page_html_file_path";
            info($command);
            $command_result = Process::run($command);
            if ($command_result->successful()) {
                $pages_pdf_files .= $page_pdf_file_path . ' ';
            } else {
                info('server error');
                info($command_result->errorOutput());
            }

        }
        info($pages_pdf_files);
        if (!empty($pages_pdf_files)) {
            $command_result = Process::run("pdfunite $pages_pdf_files $output_pdf");
            return $command_result->successful();
        }
        return false;
    }

    public function getDownloadURL($style = false)
    {
        if (!$style) {
            $style = $this->style;
        }
        $templates_path = storage_path($this->templates_folder);
        $pages = $this->getPages();
        // $pdf_converter_js_script_path = storage_path('app/private/pdfexport/pdfexport.js');
        // $pdf_converter_js_script_path = resource_path('pdfexport/pdfexport.js');
        $output_pdf = $templates_path . $this->user->id . '-' . $this->id . '-' . $style . '.pdf';

        if (file_exists($output_pdf)) {
            unlink($output_pdf);
        }
        $pages_pdf_files = '';
        $result = [
            'status' => 'failed',
            'message' => 'Something went wrong',
        ];
        if (!is_dir($templates_path)) {
            $result['message'] = 'Server error, code: 1';
            return $result;
        }
        if (count($pages) == 0) {
            $result['message'] = 'Server error, code: 2';
            return $result;
        }
        // if (!is_file($pdf_converter_js_script_path)) {
        //     $result['message'] = 'Server error, code: 3';
        //     return $result;
        // }
        foreach ($pages as $page) {
            $page_html_content = $this->template->renderResume($this, $page, 'pdf', $style);
            $page_html_file_name = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.html';
            $page_pdf_file_name = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.pdf';
            $page_html_file_path = $templates_path . $page_html_file_name;
            $page_pdf_file_path = $templates_path . $page_pdf_file_name;
            if (file_exists($page_html_file_path)) {
                unlink($page_html_file_path);
            }
            file_put_contents($page_html_file_path, $page_html_content);
            if (!is_file($page_html_file_path)) {
                $result['message'] = 'Server error, code: 4';
                break;
            }
            // $driver = app()->environment('production') ? 'firefox' : 'chrome';
            $driver = 'chrome';
            $response = Http::asForm()->post('127.0.0.1:9090', [
                'source' => $page_html_file_path,
                'destination' => $page_pdf_file_path,
                'driver' => $driver
            ]);
            // if ($command_result->successful()) {
            if ($response == 'ok') {
                $pages_pdf_files .= $page_pdf_file_path . ' ';
            } else {
                $result['message'] = 'Server error, code: 5';
                break;
            }
        }
        $command_result = Process::run("pdfunite $pages_pdf_files $output_pdf");
        if ($command_result->successful()) {
            $result['status'] = 'success';
            $result['message'] = 'Success';
            $result['download_url'] = route('download', [$this]);
        } else {
            $result['message'] = 'Server error, code: 6, ' . "pdfunite $pages_pdf_files $output_pdf" . ',' . $command_result->exitCode() . ', ' . $command_result->errorOutput();
            info('pdfunite error');
            info($command_result->errorOutput());
        }
        return $result;
    }
    public function getResumePDFFilePath($style = 'default')
    {
        $templates_path = storage_path($this->templates_folder);
        $output_pdf_name = $this->user->id . '-' . $this->id . '-' . $style . '.pdf';
        $output_pdf_path = $templates_path . $output_pdf_name;
        if (!file_exists($output_pdf_path)) {
            return false;
        }
        return $output_pdf_path;
    }

    public function getResumeDownloadContent($style = 'default')
    {
        $templates_path = storage_path($this->templates_folder);
        $output_pdf_name = $this->user->id . '-' . $this->id . '-' . $style . '.pdf';
        $download_file_name = $this->template->name . '-' . $this->name . '-' . $style;
        $output_pdf_path = $templates_path . $output_pdf_name;
        if (!file_exists($output_pdf_path)) {
            return false;
        }
        return Storage::download($this->download_templates_folder . $output_pdf_name, $download_file_name);
    }

    public function deleteFiles()
    {
        $styles = $this->template->styleNames();
        $templates_path = $this->getTemplatesFolder();
        $pages = $this->getPages();
        foreach ($pages as $page) {
            $page_html_file_name = $this->user->id . '-' . $this->id . '-' . $page . '.html';
            $page_pdf_file_name = $this->user->id . '-' . $this->id . '.pdf';
            $page_pdf_file_name_page = $this->user->id . '-' . $this->id . '-' . $page . '.pdf';
            $page_html_file_path = $templates_path . $page_html_file_name;
            $page_pdf_file_path = $templates_path . $page_pdf_file_name;
            $page_pdf_file_name_page_path = $templates_path . $page_pdf_file_name_page;
            foreach ($styles as $style) {
                $page_html_file_name_style = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.html';
                $page_pdf_file_name_style = $this->user->id . '-' . $this->id . '-' . $style . '.pdf';
                $page_pdf_file_name_page_style = $this->user->id . '-' . $this->id . '-' . $page . '-' . $style . '.pdf';
                $page_html_file_path_style = $templates_path . $page_html_file_name_style;
                $page_pdf_file_path_style = $templates_path . $page_pdf_file_name_style;
                $page_pdf_file_name_page_path_style = $templates_path . $page_pdf_file_name_page_style;
                if (is_file($page_html_file_path_style)) {
                    unlink($page_html_file_path_style);
                }
                if (is_file($page_pdf_file_path_style)) {
                    unlink($page_pdf_file_path_style);
                }
                if (is_file($page_pdf_file_name_page_path_style)) {
                    unlink($page_pdf_file_name_page_path_style);
                }
                # code...
            }
            if (is_file($page_html_file_path)) {
                unlink($page_html_file_path);
            }
            if (is_file($page_pdf_file_path)) {
                unlink($page_pdf_file_path);
            }
            if (is_file($page_pdf_file_name_page_path)) {
                unlink($page_pdf_file_name_page_path);
            }

        }
    }

    public function transparentImageExists()
    {
        $transparent_profile_image = $this->user->profile->transparent_profile_image;
        if (!$transparent_profile_image) {
            return false;
        }
        $transparent_image_path = storage_path("app/public/{$transparent_profile_image}");
        if (is_file($transparent_image_path)) {
            return true;
        }
        return false;
    }

    public function styleName()
    {
        $styleInfo = config('site.styles', []);
        if (!empty($styleInfo[$this->style])) {
            return $styleInfo[$this->style]['title'];
        }
        return __('Default');
    }

    public function getWidgetsArray($page)
    {
        $widgets = [];
        $html = $this->template->html($page);
        $dom = new DomQuery($html);
        $this->template->setDOM($dom);
        $this->template->getWidgetTemplates();
        $sections = $dom->find('.section');
        foreach ($sections as $key => $section) {
            $sectionName = $section->attr('name');
            $widgets[$sectionName] = [];
            $blocks = $this->blocks()->where('resume_page', $page)->orderBy('order')->get();
            foreach ($blocks as $key => $block) {
                if ($block->section == $sectionName) {
                    // if ($block->active) {
                    $widgets[$sectionName][] = $this->template->renderBlock($block, $this->user, true);
                    // }
                }
            }
        }
        return $widgets;
    }

}

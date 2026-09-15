<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function __invoke(Resume $resume)
    {
        if (auth()) {
            if (!auth()->user()->isAdmin() && auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        $blocks = $resume->blocks();
        $pages = array_values($resume->blocks()->get()->pluck('resume_page')->unique()->toArray());
        sort($pages);
        $html = '';
        // $html = $resume->template->viewResumePage($resume);
        foreach ($pages as $key => $page) {
            $html .= $resume->template->renderResume($resume, $page, 'canvas', $resume->style);
        }
        $pageCSS = '<style>body{background: repeating-linear-gradient(45deg,#eee,#eee 10px,#ddd 10px,#ddd 20px)!important;}.page{margin:0 auto;border-radius:0;box-shadow:0 0 7px -2px rgba(0,0,0,.5);overflow:hidden}</style>';
        // $pageCSS = '';
        // border:1px #979797 solid;
        $html = str_replace('<style id="view"></style>', $pageCSS, $html);
        $html = str_replace('{{title}}', __('Preview'), $html);
        if (auth()->user()->isAdmin()) {
            return view('dashboard.view', compact('html'));
        }
        if ($resume->template->plan) {
            if (auth()->user()->canAccess($resume->template->plan->slug)) {
                return view('dashboard.view', compact('html'));
            }
        } else {
            if (auth()->user()->canAccess('free')) {
                return view('dashboard.view', compact('html'));
            }
        }

        return redirect(route('subscription'));
    }
}

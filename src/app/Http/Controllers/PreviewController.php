<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Template;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function __invoke(Resume $resume, $page = 1)
    {
        if (auth()) {
            if (auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        $html = $resume->template->renderResume($resume, (int) $page, 'canvas', $resume->style);

        return view('dashboard.preview', compact('html'));
    }
}

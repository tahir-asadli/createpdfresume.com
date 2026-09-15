<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class DownloadController extends Controller
{
    public function __invoke(Resume $resume, string $style = null)
    {
        if (auth()) {
            if (!auth()->user()->isAdmin() && auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        $styleInfo = config('site.styles', []);
        if (empty($styleInfo[$style])) {
            return __('Template style is not selected!');
        }
        $resumeContent = $resume->getResumeDownloadContent($style);
        if (!$resumeContent) {
            return view('dashboard.download_error');
        }
        return $resumeContent;
    }
}

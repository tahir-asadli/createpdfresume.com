<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Template;
use Illuminate\Http\Request;
use Rct567\DomQuery\DomQuery;

class BuilderController extends Controller
{
    public function __invoke(Resume $resume, int $page = 1)
    {

        if (auth()) {
            if (auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        if ($page > auth()->user()->PDFPageLimit()) {
            return abort(401);
        }
        if ($resume->template->plan) {
            if (auth()->user()->canAccess($resume->template->plan->slug)) {
                return view('dashboard.builder', compact('resume', 'page'));
            }
        } else {
            if (!$resume->template->plan && auth()->user()->canAccess('free')) {
                return view('dashboard.builder', compact('resume', 'page'));
            }
        }
        return redirect(route('subscription'));
    }

    public function renderTemplate(Resume $resume, int $page)
    {
        if (auth()) {
            if (auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        $widgets = $resume->getWidgetsArray($page);
        return [
            'status' => 'success',
            'message' => '',
            'widgets' => $widgets,
            'html' => view('dashboard.new-builder-template-render', compact('resume', 'page'))->render()
        ];
    }
    public function new(Resume $resume, int $page = 1)
    {

        if (auth()) {
            if (auth()->user()->id != $resume->user->id) {
                return __('Not allowed!');
            }
        }
        if ($page > auth()->user()->PDFPageLimit()) {
            return abort(401);
        }

        // $widgets = [];
        // $html = $resume->template->html($page);
        // $dom = new DomQuery($html);
        // $resume->template->setDOM($dom);
        // $resume->template->getWidgetTemplates();
        // $sections = $dom->find('.section');
        // foreach ($sections as $key => $section) {
        //     $sectionName = $section->attr('name');
        //     $widgets[$sectionName] = [];
        //     $blocks = $resume->blocks()->where('resume_page', $page)->orderBy('order')->get();
        //     foreach ($blocks as $key => $block) {
        //         if ($block->section == $sectionName) {
        //             if ($block->active) {
        //                 $widgets[$sectionName][] = $resume->template->renderBlock($block, $resume->user, true);
        //             }
        //         }
        //     }
        // }
        // dd($widgets);
        $widgets = $resume->getWidgetsArray($page);
        if ($resume->template->plan) {
            if (auth()->user()->canAccess($resume->template->plan->slug)) {
                return view('dashboard.new-builder', compact('resume', 'page', 'widgets'));
            }
        } else {
            if (!$resume->template->plan && auth()->user()->canAccess('free')) {
                return view('dashboard.new-builder', compact('resume', 'page', 'widgets'));
            }
        }
        return redirect(route('subscription'));
    }
}

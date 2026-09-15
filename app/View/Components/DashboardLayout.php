<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    // Resume Content
    // Sections & Extras
    // Resume Management
    // Account
    public function render(): View|Closure|string
    {
        $navigations = [
            'profile' => [
                'name' => __('Profile'),
                'route' => 'profile',
                'class' => 'text-black',
                'icon' => 'icons.account',
                'group' => 'resume-content',
            ],
            'experiences' => [
                'name' => __('Work experience'),
                'route' => 'experiences',
                'icon' => 'icons.briefcase',
                'group' => 'resume-content',
            ],
            'educations' => [
                'name' => __('Education'),
                'route' => 'educations',
                'icon' => 'icons.cap',
                'group' => 'resume-content',
            ],
            'skills' => [
                'name' => __('Skills'),
                'route' => 'skills',
                'icon' => 'icons.brain',
                'group' => 'resume-content',
            ],
            'languages' => [
                'name' => __('Languages'),
                'route' => 'languages',
                'icon' => 'icons.languages',
                'group' => 'resume-content',
            ],
            'Templates' => [
                'name' => __('Templates'),
                'route' => 'templates',
                'icon' => 'icons.template',
                'group' => 'resume-management',
            ],
            'resumes' => [
                'name' => __('Resumes'),
                'route' => 'resumes',
                'icon' => 'icons.resume',
                'group' => 'resume-management',
            ],
            'import' => [
                'name' => __('Import'),
                'route' => 'import',
                // 'class' => '!font-bold !text-orange-500',
                'icon' => 'icons.import',
                'group' => 'resume-management',
            ],
            'new-resume' => [
                'name' => __('New resume'),
                'route' => 'new-resume',
                // 'class' => '!font-bold !text-orange-500',
                'icon' => 'icons.plus',
                'group' => 'resume-management',
            ],
            // 'subscription' => [
            //     'name' => __('Subscription'),
            //     'route' => 'subscription',
            //     'icon' => 'icons.badge',
            // ],
            'socials' => [
                'name' => __('Socials'),
                'route' => 'socials',
                'icon' => 'icons.comment',
                'group' => 'sections-extras',
            ],
            'projects' => [
                'name' => __('Projects'),
                'route' => 'projects',
                'icon' => 'icons.bulb',
                'group' => 'sections-extras',
            ],
            'awards' => [
                'name' => __('Awards'),
                'route' => 'awards',
                'icon' => 'icons.cup',
                'group' => 'sections-extras',
            ],
            'certificates' => [
                'name' => __('Certificates'),
                'route' => 'certificates',
                'icon' => 'icons.certificate',
                'group' => 'sections-extras',
            ],
            'hobbies' => [
                'name' => __('Hobbies'),
                'route' => 'hobbies',
                'icon' => 'icons.camera',
                'group' => 'sections-extras',
            ],
            'references' => [
                'name' => __('References'),
                'route' => 'references',
                'icon' => 'icons.reference',
                'group' => 'sections-extras',
            ],
            'account' => [
                'name' => __('Account'),
                'route' => 'billing',
                'icon' => 'icons.account',
                'group' => 'account',
            ],
            'signout' => [
                'name' => __('Sign Out'),
                'route' => 'signout',
                'icon' => 'icons.logout',
                'group' => 'account',
            ],
        ];
        $informationPages = [
            'profile',
            'skills',
            'languages',
            'experiences',
            'educations',
            'socials',
            'projects',
            'awards',
            'certificates',
            'hobbies',
            'references'
        ];
        $isInformationPage = in_array(request()->segment(2), $informationPages);
        return view('layouts.dashboard', compact('navigations', 'isInformationPage'));
    }
}

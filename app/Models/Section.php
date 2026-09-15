<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $casts = [
        'section' => \App\Enums\Section::class,
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function nameAz()
    {
        //         Header = 'header';
        // Sidebar = 'sidebar';
        // Content = 'content';
        // Footer = 'footer';
        // HeaderLeft = 'header-left';
        // HeaderRight = 'header-right';
        // MiddleLeft = 'middle-left';
        // MiddleRight = 'middle-right';
        // FooterLeft = 'footer-left';
        // FooterRight = 'footer-right';
        switch ($this->section->name) {
            case 'Header':
                return __('Header');
            case 'Sidebar':
                return __('Side');
            case 'Content':
                return __('Content');
            case 'Footer':
                return __('Bottom');
            case 'HeaderLeft':
                return __('Top left');
            case 'HeaderRight':
                return __('Top right');
            case 'HeaderMiddle':
                return __('Top middle');
            case 'MiddleLeft':
                return __('Middle left');
            case 'MiddleRight':
                return __('Middle right');
            case 'FooterLeft':
                return __('Bottom left');
            case 'FooterMiddle':
                return __('Bottom middle');
            case 'FooterRight':
                return __('Bottom right');
            default:
                return $this->section->name;
        }
    }
}

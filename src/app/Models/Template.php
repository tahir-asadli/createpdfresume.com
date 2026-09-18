<?php

namespace App\Models;

use App\Models\Traits\TemplateRenderer;
use App\Models\Traits\BlockRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Rct567\DomQuery\DomQuery;

class Template extends Model
{

    use TemplateRenderer, BlockRenderer;

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function html($page = 1)
    {

        $this->folderPath = resource_path('templates/' . $this->folder);
        $templatePath = $this->folderPath . '/index.html';
        $html = file_get_contents($templatePath);
        $html = str_replace('{{page}}', $page, $html);
        return $html;
    }

    public function builderHTML($page = 1)
    {
        $html = $this->html($page);
        $dom = new DomQuery($html);
        $body = $dom->find('body');
        $sections = $dom->find('.section');
        foreach ($sections as $key => $section) {
            $widgets = $section->find('.widget');
            $section->append('<span class="section-name" data-name="' . sectionNameBySlug($section->attr('name')) . '"></span><button class="section-add-widget">+</button>');
            // $section->append('<span class="section-outline"></span>');
            foreach ($widgets as $key => $widget) {
                $widget->remove();
            }
        }
        return $body->getInnerHtml();
    }

    public static function availableTemplates()
    {
        if (auth()->user()->hasSubscription()) {
            $plan = auth()->user()->subscription->plan;
            $planIds = [$plan->id];
            if ($plan->slug == 'premium') {
                $standartPlan = Plan::where('slug', 'standart')->first();
                if ($standartPlan) {
                    $planIds[] = $standartPlan->id;
                }
            }
            return Template::active()->where(function ($query) use ($planIds) {
                return $query->whereIn('plan_id', $planIds)->orWhere('plan_id', null);
            })->orderBy('plan_id', 'asc')->get();
        }
        return Template::active()->where('plan_id', null)->orderBy('plan_id', 'asc')->get();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
        // $subscription = auth()->user()->subscription()->active()->first();
        // if ($subscription) {
        // } else {
        //     return $query->where('plan_id', 99999999);
        // }
    }
    public function scopeFree($query)
    {
        return $query->where('plan_id', null);
    }

    public function available()
    {
        if ($this->plan) {
            if (auth()->user()->hasSubscription()) {
                if (auth()->user()->subscription->plan->slug == 'premium') {
                    return $this->plan->slug == auth()->user()->subscription->plan->slug || $this->plan->slug == 'standart';

                } else {
                    return $this->plan->slug == auth()->user()->subscription->plan->slug;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function cover()
    {
        if ($this->image) {
            return '/template-images/' . $this->image;
        }
        return null;
    }

    public function thumb()
    {
        if ($this->image) {
            $pathinfo = pathinfo($this->image);
            return '/template-images/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension'];
        }
        return '/template-images/' . $this->image;
    }

    public function styleArray()
    {
        $styleInfo = config('site.styles', []);
        if ($this->styles == 'default') {
            return [];
        }
        if ($this->styles) {
            $styles = array_merge(['default'], explode(',', $this->styles));
            if (count($styles) > 1) {
                $filteredStyles = [];
                foreach ($styles as $style) {
                    if (!empty($styleInfo[$style])) {
                        $filteredStyles[] = $styleInfo[$style];
                    }
                }
                return $filteredStyles;
            } else {
                return [];
            }
        }
        return [];
    }

    public function styleColorBySlug($slug = 'default')
    {
        $styles = $this->styleArray();
        $color = 'white';
        foreach ($styles as $key => $style) {
            if ($style['slug'] == $slug) {
                $color = $style['color'];
                break;
            }
        }
        return $color;
    }

    public function styleNames()
    {
        if ($this->styles) {
            return array_merge(['default'], explode(',', $this->styles));
        }
        return ['default'];
    }
}

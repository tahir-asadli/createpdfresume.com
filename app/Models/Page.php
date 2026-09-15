<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    public static function siteMapPages()
    {

        $pages = [];
        $langs = ['az', 'tr', 'ru'];
        $pages[] = [
            'loc' => config('app.url'),
            'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
            'image' => config('app.url') . 'site/logo.svg',
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        $pages[] = [
            'loc' => config('app.url') . 'privacy-policy',
            'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
            'image' => config('site.logo'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        $pages[] = [
            'loc' => config('app.url') . 'about',
            'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
            'image' => config('site.logo'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        $pages[] = [
            'loc' => config('app.url') . 'contact',
            'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
            'image' => config('site.logo'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        foreach ($langs as $key => $lang) {
            $pages[] = [
                'loc' => config('app.url') . '' . $lang,
                'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
                'image' => config('app.url') . 'site/logo.svg',
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
            $pages[] = [
                'loc' => config('app.url') . '' . $lang . '/privacy-policy',
                'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
                'image' => config('site.logo'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
            $pages[] = [
                'loc' => config('app.url') . '' . $lang . '/about',
                'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
                'image' => config('site.logo'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
            $pages[] = [
                'loc' => config('app.url') . '' . $lang . '/contact',
                'lastmod' => now("Asia/Baku")->format("Y-m-d\Th:m:s+00:00"),
                'image' => config('site.logo'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
        }
        return $pages;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Block extends Model implements Sortable
{
    use SortableTrait;
    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'active' => 'boolean',
        'uppercase' => 'boolean',
        'radius' => 'integer',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('section', $this->section);
    }


    public function effects()
    {
        return [
            'effect-1',
            'effect-2',
            'effect-3',
            'effect-4',
            'effect-5',
            'effect-6',
            'effect-7',
            'effect-8',
            'effect-9'
        ];
    }
    public function blendModeGroups()
    {
        return [
            [
                'normal',
                'multiply',
                'screen',
            ],
            [
                'overlay',
                'darken',
                'lighten',
            ],
            [
                'color-burn',
                'saturation',
                'luminosity'
            ],
        ];
    }

    public function blendModes()
    {
        return [
            'normal',
            'multiply',
            'screen',
            'overlay',
            'darken',
            'lighten',
            'color-burn',
            'saturation',
            'luminosity'
        ];
    }
}

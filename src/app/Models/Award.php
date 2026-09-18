<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Award extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery(): Builder
    {
        return static::query()->where('user_id', $this->user->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function year($format)
    {
        $dateFormat = "Y";
        if ($format == "month") {
            $dateFormat = "m/Y";
        }
        if ($format == "day") {
            $dateFormat = "d/m/Y";
        }
        if ($format == "monthname") {
            $dateFormat = "M Y";
        }
        if ($format == "daymonthname") {
            $dateFormat = "d M Y";
        }
        $date = $this->date ? ucwords($this->date->translatedFormat($dateFormat)) : '';
        if ($date != '') {
            return "<span>{$date}</span>";
        }
    }
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    protected $casts = [
        'date' => 'datetime',
    ];
}

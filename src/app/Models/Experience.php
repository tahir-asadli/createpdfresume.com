<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Experience extends Model implements Sortable
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

    public function years($format)
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
        $startDate = $this->start_date ? ucwords($this->start_date->translatedFormat($dateFormat)) : '';
        $endDate = $this->end_date ? ucwords($this->end_date->translatedFormat($dateFormat)) : '';
        if ($startDate != '' && $endDate != '') {
            return "<span>{$startDate}</span><span>{$endDate}</span>";
        }
        if ($startDate != '' && $endDate == '') {
            return "<span>{$startDate}</span><span>" . __('Present') . "</span>";
        }
        if ($startDate != '') {
            return "<span>{$startDate}</span>";
        }
        if ($endDate != '') {
            return "<span>{$endDate}</span>";
        }
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];
}

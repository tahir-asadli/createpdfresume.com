<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cknow\Money\Casts\MoneyIntegerCast;

class Plan extends Model
{
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    protected $casts = [
        // 'price' => MoneyIntegerCast::class . ':AZN'
    ];

    public function priceStr()
    {
        return priceWithCurrency($this->price);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function featuresCollection()
    {
        return collect(explode(PHP_EOL, $this->features))->filter(fn($feature) => trim($feature) != '');
    }

    public function featuresArray()
    {
        return explode(PHP_EOL, $this->features);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function priceStrUSDAZN()
    {

        return priceUSD($this->plan->usd) . ' = ' . priceAZNLong($this->plan->price);
    }

    public function priceStr()
    {
        return priceWithCurrency($this->plan->price);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRenew($query)
    {
        return $query->where('renew', true);
    }

    public function scopeDue($query)
    {
        return $query->where('ends_at', '<=', now());
    }

    protected function casts(): array
    {
        return [
            'ends_at' => 'datetime',
        ];
    }
}

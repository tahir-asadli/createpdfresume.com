<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeVerified($query)
    {
        return $query->where('verified', true)->where('active', true)->where('expires_at', '>', now());
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function date()
    {
        return $this->expires_at->format("m/y");
    }
}

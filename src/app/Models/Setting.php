<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function get($key, $default = null)
    {
        $record = self::where('key', $key)->first();
        if ($record) {
            return $record->value;
        }
        return $default;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    public function firstName()
    {
        return explode(' ', trim($this->name))[0] ?? '';

    }

}

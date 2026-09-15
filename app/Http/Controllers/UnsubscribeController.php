<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    public function __invoke(Customer $customer)
    {
        $customer->unsubscribed = true;
        $customer->save();
        return view('unsubscribe');
    }
}

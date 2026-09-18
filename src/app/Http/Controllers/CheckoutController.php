<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Plan $plan)
    {
        $hasSubscription = auth()->user()->hasSubscription();
        return view('checkout', compact('plan', 'hasSubscription'));
    }
}

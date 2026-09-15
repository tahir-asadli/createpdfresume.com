<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function enableRenew()
    {
        if (auth()->user()->hasSubscription()) {
            auth()->user()->subscription()->update(['renew' => true]);
            return redirect()->route('subscription')->with('success', __('Subscription renewal activated'));
        }
        return redirect()->route('subscription')->with('error', __('You have no active subscription'));
    }

    public function disableRenew()
    {
        if (auth()->user()->hasSubscription()) {
            auth()->user()->subscription()->update(['renew' => false]);
            return redirect()->route('subscription')->with('success', __('Subscription renewal deactivated'));
        }
        return redirect()->route('subscription')->with('error', __('You have no active subscription'));
    }
}

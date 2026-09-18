<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Mail\PaymentsProcessed;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ViewsCronJob
{
  public static function run()
  {
    $threeHoursAgo = Carbon::now()->subHours(2);

    View::where('created_at', '<', $threeHoursAgo)->where('duration', 0)->delete();
    $monthAgo = Carbon::now()->subMonth(1);

    View::where('created_at', '<', $monthAgo)->delete();
  }

}
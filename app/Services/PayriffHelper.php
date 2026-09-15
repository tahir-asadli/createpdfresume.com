<?php

namespace App\Services;

use App\Mail\PaymentsProcessed;
use App\Models\Card;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PayriffHelper
{

  public static function lang()
  {
    $langs = ['az', 'en', 'ru'];
    $lang = App::currentLocale();
    if (in_array($lang, $langs)) {
      return $lang;
    }
    return 'en';
  }
}

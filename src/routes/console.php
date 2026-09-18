<?php

use App\Services\SubscriptionCronJob;
use App\Services\ViewsCronJob;

// Schedule::call(function () {
//     SubscriptionCronJob::run();
//     // })->everyMinute();
// })->dailyAt('12:00');

Schedule::call(function () {
  ViewsCronJob::run();
})->hourly();

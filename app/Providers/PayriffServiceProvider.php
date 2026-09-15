<?php

namespace App\Providers;

use App\Services\EpointClient;
use App\Services\PayriffClient;
use Illuminate\Support\ServiceProvider;

class PayriffServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    app()->bind('payriff', function () {
      $currency = config('payriff.currency');
      $private_key = config('payriff.private_key');
      $base_url = config('payriff.base_url');
      return new PayriffClient($private_key, $base_url, $currency);
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}

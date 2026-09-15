<?php

namespace App\Providers;

use App\Services\EpointClient;
use Illuminate\Support\ServiceProvider;

class EpointServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    app()->bind('epoint', function () {
      $public_key = config('epoint.public_key');
      $private_key = config('epoint.private_key');
      $base_url = config('epoint.base_url');
      return new EpointClient($public_key, $private_key, $base_url);
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

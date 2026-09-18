<?php

namespace App\Http\Responses;

use App\Filament\Resources\OrderResource;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Livewire\Features\SupportRedirects\Redirector;

// class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
// {
//   public function toResponse($request): RedirectResponse|Redirector
//   {
//     // Here, you can define which resource and which page you want to redirect to
//     return redirect('/app');
//   }
// }

// use App\Filament\Customer\Resources\MyOrdersResource;
// use App\Filament\Resources\OrderResource;
// use Filament\Facades\Filament;
// use Illuminate\Http\RedirectResponse;
// use Livewire\Features\SupportRedirects\Redirector;

// class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
// {
//   public function toResponse($request): RedirectResponse|Redirector
//   {
//     // // You can use the Filament facade to get the current panel and check the ID
//     // if (Filament::getCurrentPanel()->getId() === 'admin') {
//     //     return redirect()->to(OrderResource::getUrl('index'));
//     // }

//     if (Filament::getCurrentPanel()->getId() === 'app') {
//       return redirect()->route('dashboard');
//     }

//     return parent::toResponse($request);
//   }
// }

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
  public function toResponse($request): RedirectResponse|Redirector
  {
    if (Filament::getCurrentPanel()->getId() === 'app') {
      return redirect()->intended();
    }
    // Here, you can define which resource and which page you want to redirect to
    return parent::toResponse($request);
  }
}
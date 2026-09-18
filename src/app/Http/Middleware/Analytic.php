<?php

namespace App\Http\Middleware;

use App\Models\View;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as FView;
use Illuminate\Support\Str;

class Analytic
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $ip = $request->ip();
    if(isVisitorBlockedByIp($ip)){
      abort(403);
    }
    $uri = (string) $request->path();
    if (auth()->check()) {
        $ip = $request->ip();
        $user = auth()->user(); 
        $user->ip = $ip;
        $user->timestamps = false;
        $user->saveQuietly(); 
    }
    $blacklist = [
      'log-viewer',
      'livewire/update',
      'ping'
    ];
    if (in_array($uri, $blacklist) || Str::startsWith($uri, 'dashboard')) {
      return $next($request);
    }

    $uid = null;
    $userAgent = $request->header('User-Agent');
    $method = $request->method();
    $countryCode = !empty($_SERVER['HTTP_CF_IPCOUNTRY']) ? $_SERVER['HTTP_CF_IPCOUNTRY'] : '';
    $country = $countryCode ? countryNameByCode($countryCode) : 'No country';
    $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $uuid = uuid_token();
    $expires = 60 * 24;
    // $expires = 1;
    // $response->withCookie(cookie('pid', $uuid, $expires));
    FView::share('pageuuid', $uuid);
    $response = $next($request);
    if (!$request->hasCookie('gid')) {
      $gid = Str::uuid()->toString();
      $cookie = cookie('gid', $gid, $expires);
      $response->headers->setCookie($cookie);
      // $response->withCookie(cookie('gid', $gid, $expires));
    } else {
      $gid = $request->cookie('gid');
    }
    if (auth()->check()) {
      $user = auth()->user(); 
      // $user->ip = $ip;
      // $user->timestamps = false;
      // $user->saveQuietly(); 
      $uid = $user->id;
      if ($user->isAdmin()) {
        return $response;
      }
    }
    if (is_bot($userAgent)) {
      return $response;
    }
    View::create([
      'gid' => cl($gid),
      'uid' => cl($uid),
      'uuid' => cl($uuid),
      'country' => cl($countryCode),
      'ip' => cl($ip),
      'method' => cl($method),
      'uri' => cl($uri),
      'ref' => cl($ref),
      'bot' => 0,
      'agent' => cl($userAgent),
    ]);
    return $response;
  }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);
    if ($request->uri()->path() == 'app/login' && auth()->check()) {
      return redirect('/dashboard');
    } else if ($request->uri()->path() == 'app') {
      return redirect('/dashboard');
    }
    return $response;
  }
}

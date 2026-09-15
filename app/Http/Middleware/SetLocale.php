<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check()) {
            $ip = $request->ip();
            $user = auth()->user(); 
            $user->ip = $ip;
            $user->timestamps = false;
            $user->saveQuietly(); 
        }
        if ($request->segment(1) == 'dashboard') {
            if (Auth::check()) {
                App::setLocale(auth()->user()->language);
            }
        } else {
            // $localeParam = $request->query('lang');
            // if (in_array($localeParam, ['az', 'ru', 'en', 'tr'])) {
            //     App::setLocale($localeParam);
            // }
            $localeSegment = $request->segment(1);
            // if (in_array($localeSegment, ['az', 'en'])) {
            if (in_array($localeSegment, ['az', 'en', 'tr', 'ru', 'es'])) {
                App::setLocale($localeSegment);
            }
        }
        // if ($request->isMethod('get')) {
        //     $localeSegment = $request->segment(1);
        //     $locale = app()->getLocale();
        //     $localeParam = $request->query('locale') ?: $request->segment(1);
        //     if (in_array($localeParam, ['az', 'ru'])) {
        //         $locale = $localeParam;
        //     } elseif (in_array($localeSegment, ['az', 'ru'])) {
        //         $locale = $localeSegment;
        //     }
        //     if (!$request->query('locale') && in_array($request->path(), array('app/login', 'app/register', 'app/password-reset/request'))) {

        //         $locale = $request->cookie('locale') ?? app()->getLocale();

        //         return redirect(URL::current() . '?locale=' . $locale)->with('locale', $locale);
        //     }
        //     $request->cookie('locale', $locale);
        //     app()->setLocale($locale);
        //     $response = $next($request);
        //     if ($response instanceof \Illuminate\Http\Response || $response instanceof \Illuminate\Http\RedirectResponse) {
        //         return $response->withCookie(cookie()->forever('locale', $locale))->withCookie(cookie()->forever('emlakinstallapp', "true"));
        //     }
        //     // return $next($request)->withCookie(cookie()->forever('locale', $locale));
        // }
        return $next($request);
    }
}

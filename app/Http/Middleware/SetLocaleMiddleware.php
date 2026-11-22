<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['ar', 'en'];

        $locale = $request->session()->get('locale')
            ?? $request->cookie('locale')
            ?? (Auth::check() ? Auth::user()->locale : null)
            ?? config('app.locale', 'ar');

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        return $next($request);
    }
}

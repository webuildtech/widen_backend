<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['lt', 'en'];

        $locale = null;

        if ($request->user()?->locale && in_array($request->user()->locale, $supported, true)) {
            $locale = $request->user()->locale;
        }

        $locale ??= $request->getPreferredLanguage($supported);

        $locale ??= config('app.locale');

        app()->setLocale($locale);

        return $next($request);
    }
}

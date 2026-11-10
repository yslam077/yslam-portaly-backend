<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('lang') 
            ?? $request->header('Accept-Language') 
            ?? config('app.locale', 'tm');

        $locale = strtolower(substr($locale, 0, 2));

        if (!in_array($locale, ['tm', 'ru', 'en'])) {
            $locale = 'tm';
        }

        app()->setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}
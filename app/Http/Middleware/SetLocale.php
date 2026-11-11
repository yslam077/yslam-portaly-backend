<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supported = ['tm', 'ru', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->fromQuery($request) ?? $this->fromHeader($request) ?? 'tm';

        app()->setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }

    protected function fromQuery(Request $request): ?string
    {
        $lang = $request->query('lang');

        return is_string($lang) && in_array($lang, $this->supported, true) ? $lang : null;
    }

    protected function fromHeader(Request $request): ?string
    {
        $header = (string) $request->header('Accept-Language', '');

        foreach (explode(',', $header) as $part) {
            $code = strtolower(trim(explode(';', $part)[0]));
            $short = substr($code, 0, 2);

            foreach ([$code, $short] as $candidate) {
                if (in_array($candidate, $this->supported, true)) {
                    return $candidate;
                }
            }
        }

        return null;
    }
}
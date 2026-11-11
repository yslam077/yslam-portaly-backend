<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        if (! $user) {
            return $wantsJson
                ? response()->json(['message' => __('messages.unauthorized')], 401)
                : redirect()->guest(route('admin.login'));
        }

        if (! $user->hasRole($roles)) {
            return $wantsJson
                ? response()->json(['message' => __('messages.forbidden')], 403)
                : abort(403, __('messages.forbidden'));
        }

        return $next($request);
    }
}
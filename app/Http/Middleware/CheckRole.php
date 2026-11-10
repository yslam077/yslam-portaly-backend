<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Проверяем, авторизован ли пользователь и совпадает ли его роль
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'У вас нет прав для доступа к этому разделу.');
        }

        return $next($request);
    }
}
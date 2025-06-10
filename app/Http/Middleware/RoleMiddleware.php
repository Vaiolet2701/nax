<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Проверяем, авторизован ли пользователь и имеет ли он нужную роль
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Если пользователь не имеет нужной роли, перенаправляем его на главную страницу или страницу с ошибкой
        return redirect('/')->with('error', 'У вас нет доступа к этой странице.');
    }
}

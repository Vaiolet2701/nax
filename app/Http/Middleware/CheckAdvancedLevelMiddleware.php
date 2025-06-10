<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckAdvancedLevelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->laravel_level !== 'Продвинутый') {
            return redirect()->route('home')->with('error', 'Эта функция доступна только для продвинутых пользователей');
        }

        return $next($request);
    }
}
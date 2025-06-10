<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Используем фасад Auth правильно
        if (Auth::check() && Auth::user()->isBanned()) {
            $ban = Auth::user()->activeBan();
            
            $message = $ban->permanent 
                ? "Ваш аккаунт заблокирован навсегда. Причина: {$ban->reason}"
                : "Ваш аккаунт заблокирован до {$ban->expires_at->format('d.m.Y H:i')}. Причина: {$ban->reason}";
            
            Auth::logout();
            
            return redirect()
                ->route('login')
                ->with('error', $message)
                ->withInput($request->only('email'));
        }

        return $next($request);
    }
}
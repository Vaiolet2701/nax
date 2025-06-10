<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Проверяет, что пользователь аутентифицирован и имеет роль администратора
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            Log::warning('Попытка доступа к админ-панели', [
                'user_id' => $user ? $user->id : null,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            abort(403, 'Доступ разрешен только администраторам');
        }

        return $next($request);
    }
}
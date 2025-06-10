<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ManagerMiddleware
{
    /**
     * Проверяет права менеджера
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // Разрешаем доступ админам и менеджерам
        if (!$user || (!$user->isManager() && !$user->isAdmin())) {
            Log::error('Несанкционированный доступ менеджера', [
                'route' => $request->path(),
                'user_id' => $user ? $user->id : null,
                'ip' => $request->ip()
            ]);
            
            abort(403, 'Доступ только для менеджеров и администраторов');
        }

        return $next($request);
    }
}
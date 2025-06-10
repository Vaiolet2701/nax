<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ContentManagerMiddleware
{
    /**
     * Проверяет права контент-менеджера
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->isContentManager()) {
            // Для API возвращаем JSON-ошибку
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Content manager access required',
                    'user_id' => $user ? $user->id : null
                ], 403);
            }
            
            abort(403, 'Требуются права контент-менеджера');
        }

        return $next($request);
    }
}
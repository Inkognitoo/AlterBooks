<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

/**
 * Проверяем, авторизован ли пользователь
 *
 * Class CheckAuth
 * @package App\Http\Middleware
 */
class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (blank(Auth::user())) {
            return response(view('errors.401'), 401);
        }

        return $next($request);
    }
}

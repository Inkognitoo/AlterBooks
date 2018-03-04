<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

/**
 * Проверяем, существует ли пользователь
 *
 * Class CheckUserExist
 * @package App\Http\Middleware
 */
class CheckUserExist
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
        if (blank(User::findByIdOrSlug($request->id))) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}

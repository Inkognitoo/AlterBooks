<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Проверяем, имеет пользователь право на работу с сущностью
 *
 * Class CheckUserGranted
 * @package App\Http\Middleware
 */
class CheckUserGranted
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
        if (Auth::user()->id != $request->id) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}

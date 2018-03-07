<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Auth;
use Illuminate\Http\Response;

/**
 * Проверяем, авторизован ли пользователь
 *
 * Class IsUserAuth
 * @package App\Http\Middleware\Api
 */
class IsUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            throw new ApiException(t('user.api', 'Пользователь не авторизован'), Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;



use Closure;
use Auth;

/**
 * Проверяем, авторизован ли пользователь
 *
 * Class IsUserAuth
 * @package App\Http\Middleware\Api
 */
class IsUserAuth
{
    /** @var array $out */
    private $out = [
        'success' => false,
        'code' => 401,
        'data' => [
            'message' => 'Unauthorized'
        ]
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json($this->out);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Response;

class ApiWrapper
{
    /**
     * Добавить стандартную обёртку для API.
     * Работает в паре с обработчиком ошибок (Реагирует на ApiException)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if ((bool) $response->headers->get('X-error')) {
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }

        $out = [
            'success' => true,
            'data' => $response->original,
            'errors' => []
        ];

        return response()->json($out);
    }
}

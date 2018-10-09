<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\JsonResponse;
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

        if ($response->headers->has(ApiException::ERROR_HEADER_LABEL)) {
            $response->headers->remove(ApiException::ERROR_HEADER_LABEL);

            return $response;
        }

        $out = [
            'success' => true,
            'errors' => []
        ];

        if ($response instanceof JsonResponse &&
            array_has($response->original, 'data')) {
            $out = array_merge_recursive($out, $response->original);
        } else {
            $out['data'] = $response->original;
        }

        return response()->json($out);
    }
}

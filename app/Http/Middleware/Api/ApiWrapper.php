<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiWrapper
{
    /** @var string Константа для указания того факта, что это api реквест */
    public const API_HEADER_LABEL = 'X-API-Some-Random-String-For-Not-To-Be-In-The-User-Request';

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
        $request->headers->set(self::API_HEADER_LABEL, true);

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

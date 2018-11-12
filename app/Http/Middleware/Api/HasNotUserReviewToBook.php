<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\ApiException;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверям, отсутствуют ли у пользователя активные рецензии
 *
 * Class HasNotUserReviewToBook
 * @package App\Http\Middleware\Api
 */
class HasNotUserReviewToBook
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
        $book_id = $request->book_id ?? $request->id;

        $review_active = Auth::user()->reviews()->where('book_id', $book_id)->exists();

        if ($review_active) {
            throw new ApiException('Существуют активные рецензии к книге', Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}

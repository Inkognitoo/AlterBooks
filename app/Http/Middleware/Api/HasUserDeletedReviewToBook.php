<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\ApiException;
use Auth;
use Closure;
use Illuminate\Http\Response;
use App\Models\Review;

/**
 * Проверям, отсутствуют ли у пользователя активные рецензии
 *
 * Class HasUserDeletedReviewToBook
 * @package App\Http\Middleware\Api
 */
class HasUserDeletedReviewToBook
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

        $review_deleted = Review::withTrashed() -> where('user_id', Auth::user()->id)->where('book_id', $book_id)->exists();
        if (!$review_deleted) {
            throw new ApiException('Удаленных рецензий к книге не существует', Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
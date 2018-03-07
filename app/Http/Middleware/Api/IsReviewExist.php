<?php

namespace App\Http\Middleware;

use App\Book;
use App\Exceptions\ApiException;
use App\Review;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверям, существует ли рецензия к книге
 *
 * Class IsBookExist
 * @package App\Http\Middleware\Api
 */
class IsReviewExist
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
        $review_id = $request->review_id ?? $request->id;

        $review = Review::find($review_id);
        $book = Book::findAny($request->book_id);

        if (blank($review)) {
            throw new ApiException(t('review.api', 'Рецензии не существует'), Response::HTTP_NOT_FOUND);
        }

        if ($review->book_id !== $book->id) {
            throw new ApiException(t('review.api', 'Рецензии для текущей книги не существует'), Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}

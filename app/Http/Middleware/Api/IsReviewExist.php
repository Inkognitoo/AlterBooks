<?php

namespace App\Http\Middleware;

use App\Models\Book;
use App\Exceptions\ApiException;
use App\Models\Review;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверям, существует ли данная рецензия у данной книги
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
        $book_id = $request->book_id ?? $request->id;
        $review_id = $request->review_id ?? $request->id;

        $book = Book::find($book_id);
        $review = Review::find($review_id);

        if (blank($review)) {
            throw new ApiException(t('review.api', 'Рецензии не существует'), Response::HTTP_NOT_FOUND);
        }

        if ($review->book_id !== $book->id) {
            throw new ApiException(t('review.api', 'Рецензии для текущей книги не существует'), Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}

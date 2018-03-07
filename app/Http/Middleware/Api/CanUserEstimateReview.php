<?php

namespace App\Http\Middleware\Api;

use App\Book;
use App\Exceptions\ApiException;
use App\Review;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверяем, может ли пользователь оценить рецензию
 *
 * Class CanUserLibraryBook
 * @package App\Http\Middleware\Api
 */
class CanUserEstimateReview
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
        $book_id = $request->book_id;
        $book = Book::findAny($book_id);

        $review_id = $request->review_id ?? $request->id;
        $review = Review::find($review_id);

        if (Auth::user()->isAuthor($book)) {
            throw new ApiException(t('review_estimate.api','вы не можете оставить оценку к своей собственной книге'), Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if (Auth::user()->hasReview($review)) {
            throw new ApiException(t('review_estimate.api', 'вы не можете оставить оценку к своей собственой рецензии'), Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return $next($request);
    }
}

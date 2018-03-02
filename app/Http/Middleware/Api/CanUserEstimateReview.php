<?php

namespace App\Http\Middleware\Api;

use App\Book;
use App\Review;
use Auth;
use Closure;

/**
 * Проверяем, может ли пользователь оценить рецензию
 *
 * Class CanUserLibraryBook
 * @package App\Http\Middleware\Api
 */
class CanUserEstimateReview
{
    /** @var array $out */
    private $out = [
        'success' => false,
        'code' => 403,
        'data' => [
            'message' => '',
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
        $book_id = $request->book_id;
        $book = Book::find($book_id);

        $review_id = $request->review_id ?? $request->id;
        $review = Review::find($review_id);

        if (Auth::user()->isAuthor($book)) {
            $this->out['data']['message'] = t('review_estimate.api','вы не можете оставить оценку к своей собственной книге');
            return response()->json($this->out);
        }

        if ($review->user_id == Auth::user()->id) {
            $this->out['data']['message'] = t('review_estimate.api', 'вы не можете оставить оценку к своей собственой рецензии');
            return response()->json($this->out);
        }

        return $next($request);
    }
}

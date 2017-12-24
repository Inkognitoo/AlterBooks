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
            'message' => 'You cannot estimate reviews for your own book'
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
            return response()->json($this->out);
        }

        if ($review->user_id == Auth::user()->id) {
            $this->out['success'] = false;
            $this->out['code'] = 403;
            $this->out['data']['message'] = 'You cannot estimate your own review';
            return response()->json($this->out);
        }

        return $next($request);
    }
}

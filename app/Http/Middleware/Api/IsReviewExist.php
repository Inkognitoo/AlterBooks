<?php

namespace App\Http\Middleware;

use App\Book;
use App\Review;
use Closure;

/**
 * Проверям, существует ли рецензия к книге
 *
 * Class IsBookExist
 * @package App\Http\Middleware\Api
 */
class IsReviewExist
{
    /** @var array $out */
    private $out = [
        'success' => false,
        'code' => 404,
        'data' => [
            'message' => ''
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
        $review_id = $request->review_id ?? $request->id;

        $review = Review::find($review_id);
        $book = Book::findAny($request->book_id);

        if (blank($review)) {
            $this->out['data']['message'] = t('review.api', 'Рецензии не существует');
            return response()->json($this->out);
        }

        if ($review->book_id !== $book->id) {
            $this->out['data']['message'] = t('review.api', 'Рецензии для текущей книги не существует');
            return response()->json($this->out);
        }

        return $next($request);
    }
}

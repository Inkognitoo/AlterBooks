<?php

namespace App\Http\Middleware;

use App\Models\Review;
use Closure;

/**
 * Проверям, существует ли рецензия
 *
 * Class CheckReviewExist
 * @package App\Http\Middleware
 */
class CheckReviewExist
{
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

        if (blank($review)) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}

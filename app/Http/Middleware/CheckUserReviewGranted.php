<?php

namespace App\Http\Middleware;

use App\Models\Review;
use Closure;
use Auth;

/**
 * Проверяем, имеет ли пользователь право на работу с рецензией
 *
 * Class CheckUserReviewGranted
 * @package App\Http\Middleware
 */
class CheckUserReviewGranted
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

        if (!Auth::user()->hasReview(Review::find($review_id))) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
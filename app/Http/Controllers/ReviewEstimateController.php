<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Api\CanUserEstimateReview;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\IsUserAuth;
use App\ReviewEstimate;
use Auth;
use Illuminate\Http\JsonResponse;

class ReviewEstimateController extends Controller
{
    /** @var array $out */
    private $out = [
        'success' => true,
        'code' => 200,
        'data' => [
            'message' => ''
        ]
    ];

    public function __construct()
    {
        $this->middleware(IsUserAuth::class);

        $this->middleware(IsBookExist::class);

        $this->middleware(IsReviewExist::class);

        $this->middleware(CanUserEstimateReview::class);
    }

    /**
     * Положительно оценить книгу
     *
     * @param  mixed  $book_id
     * @param  int  $id
     * @return JsonResponse
     */
    public function plus($book_id, $id)
    {
        $review_estimate = Auth::user()
            ->reviewEstimates()
            ->where('review_id', '=', $id)
            ->first()
        ;

        if (blank($review_estimate)) {
            $review_estimate = new ReviewEstimate();
            $review_estimate->user_id = Auth::user()->id;
            $review_estimate->review_id = $id;
            $review_estimate->estimate = 0;
        }

        if ($review_estimate->estimate > 0) {
            $this->out['success'] = false;
            $this->out['code'] = 400;
            $this->out['data']['message'] = t('review_estimate.api', 'положительная оценка к рецензии уже существует');

            return response()->json($this->out);
        }

        $review_estimate->estimate += 1;
        $review_estimate->save();

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = t('review_estimate.api', 'оценка к рецензии была успешно добавлена');
        $this->out['data']['estimate'] = $review_estimate->estimate;

        return response()->json($this->out);
    }

    /**
     * Отрицательно оценить книгу
     *
     * @param  mixed  $book_id
     * @param  int  $id
     * @return JsonResponse
     */
    public function minus($book_id, $id)
    {
        $review_estimate = Auth::user()
            ->reviewEstimates()
            ->where('review_id', '=', $id)
            ->first()
        ;

        if (blank($review_estimate)) {
            $review_estimate = new ReviewEstimate();
            $review_estimate->user_id = Auth::user()->id;
            $review_estimate->review_id = $id;
            $review_estimate->estimate = 0;
        }

        if ($review_estimate->estimate < 0) {
            $this->out['success'] = false;
            $this->out['code'] = 400;
            $this->out['data']['message'] = t('review_estimate.api', 'негативная оценка к рецензии уже существует');

            return response()->json($this->out);
        }

        $review_estimate->estimate -= 1;
        $review_estimate->save();

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = t('review_estimate.api', 'оценка к рецензии была успешно добавлена');
        $this->out['data']['estimate'] = $review_estimate->estimate;

        return response()->json($this->out);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Middleware\Api\CanUserEstimateReview;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\IsUserAuth;
use App\ReviewEstimate;
use Auth;
use Illuminate\Http\Response;

class ReviewEstimateController extends Controller
{

    public function __construct()
    {
        $this->middleware(IsUserAuth::class);

        $this->middleware(IsBookExist::class);

        $this->middleware(IsReviewExist::class);

        $this->middleware(CanUserEstimateReview::class);

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Положительно оценить книгу
     *
     * @param  mixed $book_id
     * @param  int $id
     * @return array
     * @throws ApiException
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
            throw new ApiException(t('review_estimate.api', 'положительная оценка к рецензии уже существует'), Response::HTTP_BAD_REQUEST);
        }

        $review_estimate->estimate += 1;
        $review_estimate->save();

        $response = [
            'message' =>  t('review_estimate.api', 'оценка к рецензии была успешно добавлена'),
            'estimate' => $review_estimate->estimate
        ];

        return $response;
    }

    /**
     * Отрицательно оценить книгу
     *
     * @param  mixed $book_id
     * @param  int $id
     * @return array
     * @throws ApiException
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
            throw new ApiException(t('review_estimate.api', 'негативная оценка к рецензии уже существует'), Response::HTTP_BAD_REQUEST);
        }

        $review_estimate->estimate -= 1;
        $review_estimate->save();

        $response = [
            'message' =>  t('review_estimate.api', 'оценка к рецензии была успешно добавлена'),
            'estimate' => $review_estimate->estimate
        ];

        return $response;
    }
}

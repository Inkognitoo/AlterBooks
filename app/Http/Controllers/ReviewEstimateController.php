<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Middleware\Api\CanUserEstimateReview;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\IsUserAuth;
use App\ReviewEstimate;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param  int  $book_id
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
            $this->out['data']['message'] = 'positive estimate already exist';

            return response()->json($this->out);
        }

        $review_estimate->estimate += 1;
        $review_estimate->save();

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = 'estimate was successfully added';
        $this->out['data']['estimate'] = $review_estimate->estimate;

        return response()->json($this->out);
    }

    /**
     * Отрицательно оценить книгу
     *
     * @param  int  $book_id
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
            $this->out['data']['message'] = 'negative estimate already exist';

            return response()->json($this->out);
        }

        $review_estimate->estimate -= 1;
        $review_estimate->save();

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = 'estimate was successfully added';
        $this->out['data']['estimate'] = $review_estimate->estimate;

        return response()->json($this->out);
    }
}

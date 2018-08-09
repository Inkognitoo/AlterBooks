<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Middleware\CheckUserReviewGranted;
use Redirect;
use Exception;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\IsUserAuth;
use App\Http\Middleware\Api\ApiWrapper;

class ReviewController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(IsUserAuth::class);
//
//        $this->middleware(IsBookExist::class);
//
//        $this->middleware(IsReviewExist::class)->only(['delete']);
//
//        $this->middleware(CheckUserReviewGranted::class)->only(['delete']);
//
//        $this->middleware(ApiWrapper::class);
    }

    /**
     * Удаляем рецензию
     *
     * @param mixed $id
     * @return Redirect
     * @throws Exception
     */
    public function delete($id)
    {
        Review::find($id)
            ->delete()
        ;

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return response()->json($response, 200);
    }
}

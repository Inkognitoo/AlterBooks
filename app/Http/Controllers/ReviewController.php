<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserCanReview;
use App\Http\Requests\ReviewCreateRequest;
use App\Review;
use Auth;
use Redirect;

class ReviewController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class);

        $this->middleware(CheckBookExist::class);

        $this->middleware(CheckUserCanReview::class);
    }

    /**
     * Создать новую рецензию
     *
     * @param ReviewCreateRequest $request
     * @param integer $book_id
     * @return Redirect
     */
    public function create(ReviewCreateRequest $request, int $book_id)
    {
        $review = new Review();

        $review->fill($request->all());
        $review->book_id = $book_id;
        Auth::user()->reviews()->save($review);

        $review->save();

        return redirect(route('book.show', ['id' => $book_id]));
    }
}

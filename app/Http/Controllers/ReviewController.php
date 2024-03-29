<?php

namespace App\Http\Controllers;

use App\Book;
use App\Review;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckReviewExist;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserCanReview;
use App\Http\Middleware\CheckUserReviewGranted;
use App\Http\Requests\ReviewCreateRequest;
use Redirect;
use Auth;
use Exception;

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

        $this->middleware(CheckReviewExist::class)->only(['delete']);

        $this->middleware(CheckUserCanReview::class)->except(['delete']);

        $this->middleware(CheckUserReviewGranted::class)->only(['delete']);
    }

    /**
     * Создать новую рецензию
     *
     * @param ReviewCreateRequest $request
     * @param mixed $book_id
     * @return Redirect
     */
    public function create(ReviewCreateRequest $request, $book_id)
    {
        $review = new Review();

        $review->fill($request->all());
        $review->book_id = Book::findAny($book_id)->id;
        Auth::user()->reviews()->save($review);

        $review->save();

        return redirect(route('book.show', ['id' => $book_id]));
    }

    /**
     * Удаляем рецензию
     *
     * @param mixed $book_id
     * @param mixed $id
     * @return Redirect
     * @throws Exception
     */
    public function delete($book_id, $id)
    {
        Review::find($id)
            ->delete()
        ;

        return redirect(route('book.show', ['id' => $book_id]))
            ->with(['status' => t('review.api', 'Рецензия была успешно удалена')]);
    }
}

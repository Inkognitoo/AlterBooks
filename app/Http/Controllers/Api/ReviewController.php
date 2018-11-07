<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Api\CanUserReview;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\CheckUserReviewGranted;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Middleware\Api\HasNotUserReviewToBook;
use App\Http\Middleware\Api\HasUserDeletedReviewToBook;
use App\Http\Requests\Api\ReviewEditRequest;
use App\Http\Requests\ReviewCreateRequest;
use App\Models\Book;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware(CanUserReview::class)->only('create');

        $this->middleware(IsBookExist::class)->only('create');

        $this->middleware(IsReviewExist::class)->only(['delete', 'edit']);

        $this->middleware(CheckUserReviewGranted::class)->only(['delete', 'edit']);

        $this->middleware(HasNotUserReviewToBook::class)->only(['create', 'restore']);

        $this->middleware(HasUserDeletedReviewToBook::class)->only(['restore']);

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Создаем рецензию
     *
     * @param ReviewCreateRequest $request
     * @param mixed $book_id
     * @return array
     * @throws \Exception
     */
    public function create(ReviewCreateRequest $request, $book_id)
    {
        $review = new Review();

        $review->fill($request->all());

        if (is_numeric($book_id)) {
            $book_id = 'id' . $book_id;
        }

        $review->book_id = Book::findAny($book_id)->id;
        Auth::user()->reviews()->save($review);

        $review->save();

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return $response;
    }

    /**
     * Удаляем рецензию
     *
     * @param mixed $book_id
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function delete($book_id, $id)
    {
        $review = Review::where('user_id', Auth::user()->id)
            ->where('book_id', $book_id)
            ->orderBy('updated_at', 'desc')
            ->first()
        ;
        $review->delete();

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return $response;
    }

    /**
     * Восстанавливаем рецензию
     *
     * @param mixed $book_id
     * @throws \Exception
     */
    public function restore($book_id)
    {
        Auth::user()
            ->reviews()
            ->onlyTrashed()
            ->where('book_id', $book_id)
            ->latest('deleted_at')
            ->first()
            ->restore()
        ;
    }

    /**
     * Редактируем рецензию
     *
     * @param ReviewEditRequest $request
     * @param mixed $book_id
     * @param int $id
     * @throws \Exception
     */
    public function edit(ReviewEditRequest $request, $book_id, $id)
    {
        $review = Auth::user()->reviews()
            ->where('book_id', $book_id)
            ->orderBy('updated_at', 'desc')
            ->first()
        ;

        $review->fill($request->all());
        $review->save();
    }
}

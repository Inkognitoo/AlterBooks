<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\CheckUserCanReview;
use App\Http\Middleware\IsBookExist;
use Auth;
use App\Models\Book;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Middleware\CheckUserReviewGranted;
use App\Exceptions\ApiException;
use App\Http\Middleware\IsReviewExist;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Middleware\Api\HasNotUserReviewToBook;
use App\Http\Middleware\Api\HasUserDeletedReviewToBook;
use App\Http\Requests\ReviewCreateRequest;

class ReviewController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckUserCanReview::class)->only('create');

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
     * @param mixed $id
     * @return array
     * @throws \Exception
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

        return $response;
    }

    /**
     * Восстанавливаем рецензию
     *
     * @param mixed $book_id
     * @return array
     * @throws \Exception
     */
    public function restore($book_id)
    {
        Review::onlyTrashed()
            ->where('user_id', Auth::user()->id)
            ->where('book_id', $book_id)
            ->orderBy('deleted_at','desc')
            ->first()
            ->restore()
        ;

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return $response;
    }

    /**
     * Редактируем рецензию
     *
     * @param ReviewCreateRequest $request
     * @param int $id
     * @param mixed $book_id
     * @return array
     * @throws \Exception
     */
    public function edit(ReviewCreateRequest $request, $book_id, $id)
    {
        $review = Review::where('user_id', Auth::user()->id)
            ->where('book_id', $book_id)
            ->orderBy('updated_at', 'desc')
            ->first()
        ;

        $review->fill($request->all());
        $review->save();

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return $response;
    }
}

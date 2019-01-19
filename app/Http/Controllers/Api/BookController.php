<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Middleware\Api\CanUserEditBook;
use App\Http\Middleware\IsBookExist;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Requests\Api\BookSearchRequest;
use App\Http\Requests\Api\BookTipsRequest;
use App\Http\Requests\Api\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\GenreResource;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Search\BookSearch;
use Exception;

class BookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(IsBookExist::class)->only('edit');

        $this->middleware(CanUserEditBook::class)->only('edit');

        $this->middleware(ApiWrapper::class)->only('edit');
    }


    /**
     * Получаем отфильтрованный список существующих книг
     *
     * @param BookSearchRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(BookSearchRequest $request)
    {
        $books_result = BookSearch::apply($request);

        return BookResource::collection($books_result->items)
            ->additional([
                'total' => $books_result->total,
                'perPage' => $books_result->per_page,
                'pageCount' => $books_result->page_count,
                'currentPage' => $books_result->current_page,
                'filtered' => $books_result->filtered,
                'sorted' => $books_result->sorted,
                'genres' => GenreResource::collection(Genre::orderBy('id')->get()),
            ])
        ;
    }

    /**
     * Список схожих имён книг
     *
     * @param BookTipsRequest $request
     * @return array
     */
    public function tips(BookTipsRequest $request)
    {
        return Book::getTips($request->title);
    }

    /**
     * Редактируем данные о книге
     *
     * @param BookUpdateRequest $request
     * @param mixed $book_id
     * @throws Exception
     */
    public function edit(BookUpdateRequest $request, $book_id)
    {
        if (is_numeric($book_id)) {
            $book_id = 'id' . $book_id;
        }

        $book = Book::findAny($book_id);

//        $book = Auth::user()
//            ->books()
//            ->where('id', $book_id)
//            ->first();

        $book->fill($request->all())->save();
    }
}

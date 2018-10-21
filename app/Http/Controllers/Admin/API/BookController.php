<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\BookSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /** @var BookSearch */
    private $book_search;

    /**
     * BookController constructor.
     * @param BookSearch $book_search
     */
    public function __construct(BookSearch $book_search)
    {
        $this->book_search = $book_search;
    }

    /**
     * Получить отфильтрованный список пользователей
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return $this->book_search->apply($request);
    }
}

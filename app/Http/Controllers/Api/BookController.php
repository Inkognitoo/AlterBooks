<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookResource;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Models\Search\BookSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    /**
     * Получаем отфильтрованный список существующих книг
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
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
}

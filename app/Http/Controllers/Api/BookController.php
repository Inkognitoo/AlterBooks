<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Search\BookSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

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
        $books = BookSearch::apply($request)
            ->with('genres')
            ->with('author')
            ->limit(10)
        ;

        return BookResource::collection($books->get())
            ->additional([
                'total' => $books->get()->count(),
            ])
        ;
    }
}

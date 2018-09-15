<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookResource;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Models\Search\BookSearch;
use Debugbar;
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
        \Log::info($request);

        $books = BookSearch::apply($request)
            ->with('genres')
            ->with('author')
            ->limit(10)
        ;

        $genres = Genre::orderBy('id');

        return BookResource::collection($books->get())
            ->additional([
                'total' => $books->get()->count(),
                'perPage' => 0,
                'pageCount' => 40,
                'currentPage' => 42,
                'filtered' => [
                    'genres' => $request->genres ?? [],
                    'title' => 'ololo',
                ],
                'sorted' => [
                    'news' => 'asc',
                ],
                'genres' => GenreResource::collection($genres->get()),
            ])
            ;
    }
}

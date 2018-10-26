<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\GenreSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{
    /** @var GenreSearch */
    private $genre_search;

    /**
     * GenreController constructor.
     * @param GenreSearch $genre_search
     */
    public function __construct(GenreSearch $genre_search)
    {
        $this->genre_search = $genre_search;
    }

    /**
     * Получить отфильтрованный список жанров
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return $this->genre_search->apply($request);
    }
}

<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\GenreSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{
    /**
     * Получить отфильтрованный список жанров
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return (new GenreSearch())->apply($request);
    }
}

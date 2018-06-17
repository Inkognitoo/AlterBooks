<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Показываем список жанров
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.genre.list');
    }

    /**
     * Показываем конкретную рецензию
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $genre = Genre::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.genre.show', ['genre' => $genre]);
    }
}

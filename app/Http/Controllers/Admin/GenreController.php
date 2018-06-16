<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Response;

class BookController extends Controller
{
    /**
     * Показываем список книг
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.book.list');
    }
}

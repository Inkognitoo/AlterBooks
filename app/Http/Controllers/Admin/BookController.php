<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Book;
use Illuminate\Http\Request;
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

    /**
     * Показываем конкретную книгу
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $book = Book::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.book.show', ['book' => $book]);
    }
}

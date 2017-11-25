<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Response;

class LandingPageController extends Controller
{
    /**
     * Отрисовать главную страницу, подгрузить в неё книги и пользователей
     *
     * @return Response
    */
    public function index() {
        $users = User::orderBy('created_at')->limit('6')->get();
        $books = Book::orderBy('updated_at')->limit('6')->get();
        return view('welcome', ['books' => $books, 'users' => $users]);
    }
}

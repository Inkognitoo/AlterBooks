<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkAuth')->except(['show', 'readPage']);

        $this->middleware('checkBookExist')->except(['createShow', 'create']);

        $this->middleware('checkUserBookGranted')->only(['editShow', 'edit']);
    }

    /**
     * Показываем профиль текущей книги.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('book.profile', [
            'book' => Book::find($id),
        ]);
    }

    /**
     * Показываем страницу редактирования профиля книги
     *
     * @param  int  $id
     * @return Response
     */
    public function editShow($id)
    {
        return view('book.edit', [
            'book' => Book::find($id)
        ]);
    }

    /**
     * Редактируем профиль книги
     *
     * @param BookUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(BookUpdateRequest $request, $id)
    {
        $book = Book::find($id);

        if (filled($request['title'])) {
            $book->title = $request['title'];
        }
        if (filled($request['cover'])) {
            $book->setCover($request['cover']);
        }
        if (filled($request['description'])) {
            $book->description = $request['description'];
        }
        if (filled($request['text'])) {
            $book->setText($request['text']);
        }
        $book->save();

        return view('book.edit', [
            'book' => $book
        ]);
    }

    /**
     * Показываем страницу создания профиля книги
     *
     * @return Response
     */
    public function createShow()
    {
        return view('book.create');
    }

    /**
     * Создаём профиль книги
     *
     * @@param BookCreateRequest $request
     * @return Response
     */
    public function create(BookCreateRequest $request)
    {
        $book = new Book();

        $book->title = $request['title'];
        if (filled($request['description'])) {
            $book->description = $request['description'];
        }

        Auth::user()->books()->save($book);

        if (filled($request['cover'])) {
            $book->setCover($request['cover']);
        }

        $book->save();

        return redirect(route('book.show', ['id' => $book->id]));
    }

    /**
     * Возвращаем конкретную страницу книги
     *
     * @param Request $request
     * @param int $id
     * @param int $page_number
     * @return Response
     */
    public function readPage(Request $request, int $id, int $page_number)
    {
        $book = Book::find($id);

        return view('book.reader', [
            'book' => $book,
            'current_page' => $page_number,
            'text' => $book->getPage($page_number)
        ]);
    }
}

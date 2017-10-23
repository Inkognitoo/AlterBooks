<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Storage;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkAuth')->except(['show']);

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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
            'text' => 'nullable|file|mimes:txt|mimetypes:text/plain',
        ]);

        if ($validator->fails()) {
            return redirect(route('book.edit.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

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
     * @@param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect(route('book.create.show'))
                ->withErrors($validator)
                ->withInput();
        }

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
     * @param int $pageNumber
     * @return Response
     */
    public function readPage(Request $request, int $id, int $pageNumber)
    {
        $book = Book::find($id);

        return response($book->getPage($pageNumber));
    }
}

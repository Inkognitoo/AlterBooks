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
            return redirect(route('book_edit_show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $book = Book::find($id);

        if (!empty($request['title'])) {
            $book->title = $request['title'];
        }
        if (!empty($request['cover'])) {
            $book->setCover($request['cover']);
        }
        if (!empty($request['description'])) {
            $book->description = $request['description'];
        }
        if (!empty($request['text'])) {
            try {
            $book->setText($request['text']);
            } catch (Exception $e){
                dd($e);
            }
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
            return redirect(route('book_create_show'))
                ->withErrors($validator)
                ->withInput();
        }

        $book = new Book();

        $book->title = $request['title'];
        if (!empty($request['description'])) {
            $book->description = $request['description'];
        }

        Auth::user()->books()->save($book);

        if (!empty($request['cover'])) {
            $book->setCover($request['cover']);
        }

        $book->save();

        return redirect(route('book_show', ['id' => $book->id]));
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

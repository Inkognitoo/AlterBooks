<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserBookGranted;
use App\Http\Requests\BookCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Exception;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class)->except(['index', 'show', 'indexVue']);

        $this->middleware(CheckBookExist::class)->except(['index', 'createShow', 'create', 'indexVue']);

        $this->middleware(CheckUserBookGranted::class)->only(['editShow', 'edit', 'delete']);
    }

    /**
     * Показываем страницу со списком существующих книг
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('book.list');
    }

    /**
     * Показываем профиль конкретной книги.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('book.profile', [
            'book' => Book::findAny($id),
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
     * @throws Exception
     */
    public function create(BookCreateRequest $request)
    {
        $book = new Book(['title' => $request->title]);
        Auth::user()->books()->save($book);

        $book->fill($request->all());
        $book->save();

        return redirect($book->url);
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
            'book' => Book::findAny($id)
        ]);
    }

    /**
     * Удаляем профиль книги
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function delete($id)
    {
        $book = Book::findAny($id);

        $book->delete();

        return redirect(Auth::user()->url)
            ->with(['status' => t('book.api', 'Книга была успешно удалена')]);
    }
}

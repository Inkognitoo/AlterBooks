<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookSearch;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserBookGranted;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class)->except(['index', 'show']);

        $this->middleware(CheckBookExist::class)->except(['index', 'createShow', 'create']);

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
        $books = BookSearch::apply($request)
            ->with('genres')
            ->with('author')
            ->paginate(10);

        return view('book.books-list', ['books' => $books]);
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

        return redirect(route('book.show', ['id' => $book->id]));
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
     * Редактируем профиль книги
     *
     * @param BookUpdateRequest $request
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function edit(BookUpdateRequest $request, $id)
    {
        $book = Book::findAny($id);

        $book->fill($request->all());
        $book->save();

        return view('book.edit', [
            'book' => $book,
            'status' => t('book.api', 'Данные были успешно обновлены')
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

        return redirect(route('user.show', ['id' => Auth::user()->id]))
            ->with(['status' => t('book.api', 'Книга была успешно удалена')]);
    }
}

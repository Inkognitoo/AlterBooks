<?php

namespace App\Http\Controllers;

use App\Book;
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
        switch ($request->sort) {
            case 'rating':
                $books = Book::get()->sortByDesc('rating');
                break;
            case 'date':
                $books = Book::orderBy('created_at', 'desc')->get();
                break;
            default:
                $books = Book::get()->sortByDesc('rating');
                break;
        }

        $books = $this->paginate($books, 10, $request->page);

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
            'status' => 'Данные были успешно обновлены'
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
            ->with(['status' => 'Книга была успешно удалена']);
    }

    /**
     * Кастомная пагинация, работающая с коллекциями
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

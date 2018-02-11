<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserBookGranted;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Requests\PageUpdateRequest;
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
        $this->middleware(CheckAuth::class)->except(['show', 'readPage', 'showBooks']);

        $this->middleware(CheckBookExist::class)->except(['createShow', 'create', 'showBooks']);

        $this->middleware(CheckUserBookGranted::class)->only(['editShow', 'edit', 'editPageShow', 'editPage', 'delete']);
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
            'book' => Book::findAny($id),
        ]);
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
     * Показываем страницу создания профиля книги
     *
     * @return Response
     */
    public function createShow()
    {
        return view('book.create');
    }

    /**
     * Возвращаем конкретную страницу книги
     *
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function readPage(int $id, int $page_number)
    {
        $book = Book::findAny($id);

        return view('book.reader.page', [
            'book' => $book,
            'current_page' => $page_number,
            'text' => $book->getPage($page_number)
        ]);
    }

    /**
     * Показываем страницу редактирования конкретной страницы книги
     *
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function editPageShow(int $id, int $page_number)
    {
        $book = Book::findAny($id);

        return view('book.reader.edit', [
            'book' => $book,
            'current_page' => $page_number,
            'text' => $book->getPage($page_number, false)
        ]);
    }

    /**
     * Показываем страницу редактирования конкретной страницы книги
     *
     * @param PageUpdateRequest $request
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function editPage(PageUpdateRequest $request, int $id, int $page_number)
    {
        $book = Book::findAny($id);

        $book->editPage($page_number, $request->text);

        return redirect(route('book.page.show', ['id' => $id, 'current_page' => $page_number]))
            ->with(['status' => 'Данные были успешно обновлены']);
    }

    /**
     * Показываем страницу со списком существующих книг
     *
     * @param Request $request
     * @return Response
     */
    public function showBooks(Request $request)
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
     * Кастомная пагинация, работающая с коллекциями
     *
     * @param array|Collection $items
     * @param int   $perPage
     * @param int  $page
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

<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Requests\PageUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Проверяем факт того, что пользователь авторизован для всех кроме
        $this->middleware('checkAuth')->except(['show', 'readPage']);

        //Проверяем факт того, что книга с данным id существует для всех кроме
        $this->middleware('checkBookExist')->except(['createShow', 'create', 'showBooks']);

        //Проверяем факт того, что пользователь имеет право на работу с книгой только для
        $this->middleware('checkUserBookGranted')->only(['editShow', 'edit', 'editPageShow', 'editPage', 'delete']);
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
     */
    public function create(BookCreateRequest $request)
    {
        $book = new Book();

        $book->fill($request->all());

        Auth::user()->books()->save($book);

        if (filled($request['cover'])) {
            $book->setCover($request['cover']);
        }
        if (filled($request['text'])) {
            $book->setText($request['text']);
        }

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
     */
    public function edit(BookUpdateRequest $request, $id)
    {
        $book = Book::findAny($id);

        $book->fill($request->all());

        if (filled($request['cover'])) {
            $book->setCover($request['cover']);
        }
        if (filled($request['text'])) {
            $book->setText($request['text']);
        }
        $book->save();

        return view('book.edit', [
            'book' => $book,
            'status' => 'Данные были успешно обновлены'
        ]);
    }

    /**
     * Удаляем профиль книги
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, $id)
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
     * @param Request $request
     * @param int $id
     * @param int $page_number
     * @return Response
     */
    public function readPage(Request $request, int $id, int $page_number)
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
     * @param Request $request
     * @param int $id
     * @param int $page_number
     * @return Response
     */
    public function editPageShow(Request $request, int $id, int $page_number)
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
     */
    public function editPage(PageUpdateRequest $request, int $id, int $page_number)
    {
        $book = Book::findAny($id);

        $book->editPage($page_number, $request['text']);

        return redirect(route('book.page.show', ['id' => $id, 'current_page' => $page_number]))
            ->with(['status' => 'Данные были успешно обновлены']);
    }

    /**
     * Показываем страницу со списком существующих книг
     *
     * @return Response
     */
    public function showBooks()
    {
        return view('book.books-list');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookUpdateRequest;
use App\Http\Requests\Admin\BookCreateRequest;
use App\Models\Admin\Book;
use App\Models\User;
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

    /**
     * Показываем страницу создания книги
     *
     * @return Response
     */
    public function createShow()
    {
        return view('admin.book.create');
    }

    /**
     * Создаём книгу
     *
     * @@param BookCreateRequest $request
     * @return Response
     */
    public function create(BookCreateRequest $request)
    {
        $book = new Book(['title' => $request->title]);
        $user = User::find($request->author_id);
        $user->books()->save($book);

        $book->fill($request->all());
        $book->save();

        return redirect(route('book.show', ['book' => $book]))
            ->with('status', 'Книга была успешно создана')
        ;
    }

    /**
     * Показываем интерфейс редактирования конкретной книги
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $book = Book::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.book.edit', ['book' => $book]);
    }

    /**
     * Обновляем информацию о пользователе
     *
     * @param BookUpdateRequest $request
     * @param $id
     * @return Response
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(BookUpdateRequest $request, $id)
    {
        $book = Book::withoutGlobalScopes()
            ->find($id)
        ;

        $book->fill($request->all());
        $book->save();

        return redirect(route('book.edit.show', ['id' => $book->id]))
            ->with('status', 'Данные были успешно обновлены')
        ;
    }
}

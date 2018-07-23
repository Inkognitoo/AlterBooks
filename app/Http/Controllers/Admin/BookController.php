<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookUpdateRequest;
use App\Models\Admin\Book;
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

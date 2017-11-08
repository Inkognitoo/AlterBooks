<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\UserUpdateRequest;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Response;
use Auth;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Проверяем факт того, что пользователь авторизован для всех кроме
        $this->middleware('checkAuth')->except(['show']);

        //Проверяем факт того, что пользователь с данным id существует для всех кроме
        $this->middleware('checkUserExist')->except(['addBookToLibrary', 'deleteBookToLibrary']);

        //Проверяем факт того, что текущий пользователь имеет права на работу с профайлом только для
        $this->middleware('checkUserGranted')->only(['editShow', 'edit']);

        //Проверяем факт того, что книга с данным id существует только для
        $this->middleware('checkBookExist')->only(['addBookToLibrary', 'deleteBookToLibrary']);
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);

        $books = optional(Auth::user())->id == $id
            ? $user->books()->withoutGlobalScope(StatusScope::class)->get()
            : $user->books;

        return view('user.profile', [
            'user' => $user,
            'books' => $books
        ]);
    }

    /**
     * Показываем страницу редактирования профиля пользователя
     *
     * @param  int  $id
     * @return Response
     */
    public function editShow()
    {
        return view('user.edit');
    }

    /**
     * Редактируем профиль пользователя
     *
     * @param UserUpdateRequest $request
     * @return Response
     */
    public function edit(UserUpdateRequest $request)
    {
        Auth::user()->fill($request->all());

        if (filled($request->password)) {
            Auth::user()->password = bcrypt($request->password);
        }
        if (filled($request->avatar)) {
            Auth::user()->setAvatar($request->avatar);
        }

        Auth::user()->save();

        return view('user.edit', [
            'status' => 'Данные были успешно обновлены'
        ]);
    }

    /**
     * Добавить книгу в библиотеку.
     *
     * @param  int  $id
     * @return Response
     */
    public function addBookToLibrary($id)
    {
        $book = Book::find($id);
        if (Auth::user()->libraryBooks()->where(['book_id' => $book->id])->get()->count() !== 0) {
            return redirect(route('book.show', ['id' => $id]));
        }

        Auth::user()->libraryBooks()->save($book);

        return redirect(route('book.show', ['id' => $id]));
    }

    /**
     * Удалить книгу из библиотеки
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteBookFromLibrary($id)
    {
        $book = Book::find($id);
        $library_book = Auth::user()->libraryBooks()->where(['book_id' => $book->id])->get();
        if ($library_book->count() === 0) {
            return redirect(route('book.show', ['id' => $id]));
        }

        $library_book->first()->pivot->delete();

        return redirect(route('book.show', ['id' => $id]));
    }
}

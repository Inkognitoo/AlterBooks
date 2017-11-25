<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserExist;
use App\Http\Middleware\CheckUserGranted;
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
        $this->middleware(CheckAuth::class)->except(['show', 'showUsers']);

        $this->middleware(CheckUserExist::class)->except(['addBookToLibrary', 'deleteBookToLibrary', 'showUsers']);

        $this->middleware(CheckUserGranted::class)->only(['editShow', 'edit']);

        $this->middleware(CheckBookExist::class)->only(['addBookToLibrary', 'deleteBookToLibrary']);
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

    /**
     * Показываем страницу со списком существующих пользователей
     *
     * @return Response
     */
    public function showUsers()
    {
        $users = User::paginate(10);

        return view('user.users-list', ['users' => $users]);
    }
}

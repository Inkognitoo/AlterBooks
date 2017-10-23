<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Storage;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkAuth')->except(['show']);

        $this->middleware('checkUserExist')->except(['addBookToLibrary', 'deleteBookToLibrary']);

        $this->middleware('checkUserGranted')->only(['editShow', 'edit']);

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
        return view('user.profile', [
            'user' => User::find($id),
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'nullable|max:255|unique:users',
            'avatar' => 'image|max:5120',
            'name' => 'nullable|max:255',
            'surname' => 'nullable|max:255',
            'patronymic' => 'nullable|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'nullable|min:6|confirmed',
            'gender' => [
                'nullable',
                Rule::in([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_NOT_INDICATED]),
            ],
            'birthday_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect(route('user.edit.show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        if (filled($request['nickname'])) {
            Auth::user()->nickname = $request['nickname'];
        }
        if (filled($request['avatar'])) {
            Auth::user()->setAvatar($request['avatar']);
        }
        if (filled($request['name'])) {
            Auth::user()->name = $request['name'];
        }
        if (filled($request['surname'])) {
            Auth::user()->surname = $request['surname'];
        }
        if (filled($request['patronymic'])) {
            Auth::user()->patronymic = $request['patronymic'];
        }
        if (filled($request['email'])) {
            Auth::user()->email = $request['email'];
        }
        if (filled($request['password'])) {
            Auth::user()->password = bcrypt($request['password']);
        }
        if (filled($request['gender'])) {
            Auth::user()->gender = $request['gender'];
        }
        if (filled($request['birthday_date'])) {
            Auth::user()->birthday_date = $request['birthday_date'];
        }

        Auth::user()->save();

        return view('user.edit');
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

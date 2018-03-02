<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserExist;
use App\Http\Middleware\CheckUserGranted;
use App\Http\Requests\UserUpdateRequest;
use App\Scopes\StatusScope;
use App\User;
use App\UserSearch;
use Illuminate\Http\Response;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class)->except(['show', 'index']);

        $this->middleware(CheckUserExist::class)->except(['index']);

        $this->middleware(CheckUserGranted::class)->only(['editShow', 'edit']);

        $this->middleware(CheckBookExist::class)->only(['addBookToLibrary', 'deleteBookToLibrary']);
    }

    /**
     * Показываем страницу со списком существующих пользователей
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = UserSearch::apply($request)->paginate(10);

        return view('user.users-list', ['users' => $users]);
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
     * @throws Exception
     */
    public function edit(UserUpdateRequest $request)
    {
        Auth::user()->fill($request->all());
        Auth::user()->save();

        return view('user.edit', [
            'status' => t('user.api', 'Данные были успешно обновлены')
        ]);
    }
    
}

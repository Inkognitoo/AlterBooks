<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckBookExist;
use App\Http\Middleware\CheckUserExist;
use App\Http\Middleware\CheckUserGranted;
use App\Http\Requests\UserUpdateRequest;
use App\Scopes\StatusScope;
use App\User;
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
        $this->middleware(CheckAuth::class)->except(['show', 'showUsers']);

        $this->middleware(CheckUserExist::class)->except(['showUsers']);

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
     * Показываем страницу со списком существующих пользователей
     *
     * @param Request $request
     * @return Response
     */
    public function showUsers(Request $request)
    {
        switch ($request->sort) {
            case 'rating':
                $users = User::get()->sortByDesc('rating');
                break;
            case 'books':
                $users = User::get()->sortByDesc('books');
                break;
            default:
                $users = User::get()->sortByDesc('rating');
                break;
        }

        $users = $this->paginate($users, 10, $request->page);

        return view('user.users-list', ['users' => $users]);
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

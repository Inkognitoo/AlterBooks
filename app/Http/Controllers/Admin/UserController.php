<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Показываем список пользователей
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.user.list');
    }

    /**
     * Показываем конкретного пользователя
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $user = User::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.user.show', ['user' => $user]);
    }

    /**
     * Показываем интерфейс редактирования конкретного пользователя
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.user.edit', ['user' => $user]);
    }

    /**
     * Обновляем информацию о пользователе
     *
     * @param UserUpdateRequest $request
     * @param $id
     * @return Response
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::withoutGlobalScopes()
            ->find($id)
        ;

        $user->fill($request->all());
        $user->save();

        return redirect(route('user.edit.show', ['id' => $user->id]))
            ->with('status', 'Данные были успешно обновлены')
        ;
    }
}

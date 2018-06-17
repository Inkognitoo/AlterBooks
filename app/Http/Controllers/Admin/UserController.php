<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}

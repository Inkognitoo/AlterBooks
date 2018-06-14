<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Показываем список пользователей
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('admin.user.list');
    }
}

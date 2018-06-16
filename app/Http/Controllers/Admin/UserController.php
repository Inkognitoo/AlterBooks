<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}

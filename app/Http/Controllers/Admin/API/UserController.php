<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Search\UserSearch;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Получить отфильтрованный список пользователей
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return (new UserSearch())->apply($request);
    }
}

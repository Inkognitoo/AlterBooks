<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\BookSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Получить отфильтрованный список пользователей
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return (new BookSearch())->apply($request);
    }
}

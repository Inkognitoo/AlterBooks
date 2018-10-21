<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Search\UserSearch;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var UserSearch */
    protected $user_search;

    /**
     * UserController constructor.
     * @param UserSearch $user_search
     */
    public function __construct(UserSearch $user_search)
    {
        $this->user_search = $user_search;
    }

    /**
     * Получить отфильтрованный список пользователей
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return $this->user_search->apply($request);
    }
}

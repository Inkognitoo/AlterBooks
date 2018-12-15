<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Middleware\CheckAuth;
use App\Http\Requests\Api\ArticleCreateRequest;
use App\Models\Blog\Article;
use Auth;

class ArticleController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class);
        $this->middleware(ApiWrapper::class);
    }

    /**
     * Создаем статью блога
     *
     * @param ArticleCreateRequest $request
     * @throws \Exception
     */
    public function create(ArticleCreateRequest $request)
    {
        $article = new Article($request->all());
        Auth::user()->articles()->save($article);
    }
}

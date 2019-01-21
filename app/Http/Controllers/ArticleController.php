<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreateRequest;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\UserArticleGranted;
use App\Models\Blog\Article;
use Auth;
use Request;

class ArticleController extends Controller
{
    /**
     * Проверка авторизации пользователя
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class);

        $this->middleware(UserArticleGranted::class)->only(['editShow', 'edit']);
    }

    /**
     * Показываем страницу создания статьи блога
     *
     * @return Response
     */
    public function createShow()
    {
        return view('blog.create');
    }

    /**
     * Создаем статью блога
     * @param ArticleCreateRequest $request
     * @return Response
     * @throws Exception
     */
    public function create(ArticleCreateRequest $request)
    {
        $article = new Article($request->all());
        Auth::user()->articles()->save($article);

        return redirect()->back();
    }

    /**
     * Показываем страницу редактирования статьи блога
     *
     * @param  string  $slug
     * @return Response
     */
    public function show($slug) {
        return view('blog.show', [
            'article' => Article::with('author')->where('slug', $slug)->first()
        ]);
    }

    /**
     * Редактируем статью блога
     *
     * @param ArticleUpdateRequest $request
     * @param Article $article
     * @return Response
     * @throws Exception
     */
    public function editShow(Article $article) {
        return view('blog.edit', [
            'article' => $article
        ]);
    }

    public function edit($slug) {

    }
}

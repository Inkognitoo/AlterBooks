<?php

namespace App\Http\Middleware;

use App\Models\Blog\Article;
use Auth;
use Closure;

/**
 * Проверяем, имеет ли пользователь право на работу со статьей
 *
 * Class UserArticleGranted
 * @package App\Http\Middleware
 */
class UserArticleGranted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $article_slug = $request->route()->slug;

        $article = Article::where('slug', $article_slug)->first();

        if (Auth::user()->id !== $article->author_id) {
            return response(view('errors.403'), 403);
        }

        app()->instance(Article::class, $article);

        return $next($request);
    }
}

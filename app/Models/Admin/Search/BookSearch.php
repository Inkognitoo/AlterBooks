<?php

namespace App\Models\Admin\Search;

use App\Http\Resources\Admin\BookCollection;
use App\Http\Resources\Admin\BookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Class BookSearch
 * @package App
 *
 */
class BookSearch extends Search
{
    /**
     * Инциализация всех необходимых переменных
     */
    public function init(): void
    {
        $this->search_class = Book::class;
        $this->collection_class = BookCollection::class;
        $this->resource_class = BookResource::class;

        $this->text_search_fields = ['title'];
    }

    /**
     * Получить список книг отфильтрованных по имени автора
     *
     * @param Builder $query
     * @param $author
     * @return Builder
     */
    public function filterByAuthor(Builder $query, $author): Builder
    {
        $author = mb_strtolower($author);

        return $query->whereHas('author', function($user) use($author) {
            $user->where(new Expression('LOWER(CONCAT(name, surname, patronymic))'), 'like', "%{$author}%");
        });
    }

    /**
     * Получить выборку книг, отсортированную по автору
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function orderByAuthor(Builder $query, $direction): Builder
    {
        return $query->leftJoin('users AS users', 'users.id', '=', 'main_model.author_id')
            ->orderBy(new Expression('CONCAT(name, surname, patronymic)'), $direction)
        ;
    }
}
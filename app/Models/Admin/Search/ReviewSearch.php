<?php

namespace App\Models\Admin\Search;

use App\Http\Resources\Admin\ReviewCollection;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Admin\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Поисковая модель рецензии
 *
 * Class ReviewSearch
 * @package App
 *
 */
class ReviewSearch extends Search
{
    /**
     * Инциализация всех необходимых переменных
     */
    public function init(): void
    {
        $this->search_class = Review::class;
        $this->collection_class = ReviewCollection::class;
        $this->resource_class = ReviewResource::class;

        $this->date_search_fields = ['created_at'];
    }

    /**
     * Получить список рецензий отфильтрованных по имени автора
     *
     * @param Builder $query
     * @param $name
     * @return Builder
     */
    public function filterByAuthor(Builder $query, $name): Builder
    {
        $name = mb_strtolower($name);

        return $query->whereHas('user', function($user) use($name) {
            /** @var $user Builder */
            $user->where(new Expression('LOWER(CONCAT(surname, name, patronymic))'), 'like', "%{$name}%");
        });
    }

    /**
     * Получить список рецензий отфильтрованных по названию книги
     *
     * @param Builder $query
     * @param $title
     * @return Builder
     */
    public function filterByBook(Builder $query, $title): Builder
    {
        $title = mb_strtolower($title);

        return $query->whereHas('book', function($user) use($title) {
            /** @var $user Builder */
            $user->where(new Expression('LOWER(title)'), 'like', "%{$title}%");
        });
    }

    /**
     * Получить выборку рецензий, отсортированную по автору
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function orderByAuthor(Builder $query, $direction): Builder
    {
        return $query->leftJoin('users AS users', 'users.id', '=', 'reviews.user_id')
            ->orderBy(new Expression('CONCAT(users.surname, users.name, users.patronymic)'), $direction)
        ;
    }

    /**
     * Получить выборку рецензий, отсортированную по названию книги
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function orderByBook(Builder $query, $direction): Builder
    {
        return $query->leftJoin('books AS books', 'books.id', '=', 'reviews.book_id')
            ->orderBy('books.title', $direction)
        ;
    }
}
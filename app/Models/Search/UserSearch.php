<?php

namespace App\Models\Search;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class BookSearch
 * @package App
 *
 */
class UserSearch
{
    /**
     * Отфильтровать и отсортировать пользователей
     *
     * @param Request $filters
     * @return Builder
     */
    public static function apply(Request $filters): Builder
    {
        $query = (new User())->newQuery();
        $query = static::applyFilters($query, $filters);

        return static::applySort($query, $filters);
    }

    /**
     * Применить имеющиеся фильтры
     *
     * @param Builder $query
     * @param Request $filters
     * @return Builder
     */
    public static function applyFilters(Builder $query, Request $filters): Builder
    {
        return $query;
    }

    /**
     * Применить указанную стортировку
     *
     * @param Builder $query
     * @param Request $filters
     * @return Builder
     */
    protected static function applySort(Builder $query, Request $filters): Builder
    {
        switch ($filters->sort) {
            case 'rating':
                $query = static::orderByRatingDesc($query);
                break;
            case 'books':
                $query = static::orderByBooksDesc($query);
                break;
            default:
                $query = static::orderByRatingDesc($query);
                break;
        }

        return $query;
    }

    /**
     * Сортировать пользователей по суммарному рейтингу их книг (а те в свою очередь по суммарному рейтингу их
     * рецензий). От большего к меньшему
     *
     * @param Builder $query
     * @return Builder
     */
    protected static function orderByRatingDesc(Builder $query): Builder
    {
        $sub_query = (new Book())->newQuery()
            ->from((new Book())->getTable() . ' AS books')
            ->select([DB::raw('books.id AS id'), DB::raw('COALESCE(MEDIAN(reviews.rating), 0) AS rating')])
            ->leftJoin((new Review())->getTable() . ' AS reviews', function ($reviews) {
                $reviews->on(['reviews.book_id' => 'books.id'])
                    ->whereNull('reviews.deleted_at');
            })
            ->groupBy('books.id')
        ;

        return $query->from((new User())->getTable() . ' AS users')
            ->select(['users.*'])
            ->leftJoin((new Book())->getTable() . ' AS books', function ($books) {
                $books->on(['books.author_id' => 'users.id'])
                    ->whereNull('books.deleted_at')
                    ->where(['books.status' => Book::STATUS_OPEN]);
            })
            ->leftJoin(DB::raw('(' . static::getRawSql($sub_query) . ') AS sub_query'), 'sub_query.id', '=', 'books.id')
            ->groupBy('users.id')
            ->orderByDesc(DB::raw('COALESCE(MEDIAN(sub_query.rating), 0)'))
            ->orderBy('users.created_at')
            ->orderBy('users.id')
        ;
    }

    /**
     * Сортировать пользователь по количеству опубликованных книг. От большего к меньшему
     *
     * @param Builder $query
     * @return Builder
     */
    protected static function orderByBooksDesc(Builder $query): Builder
    {
        return $query->withCount('books')
            ->orderByDesc('books_count')
            ->orderBy('users.id')
        ;
    }

    /**
     * Получить sql запрос вместе с заполнеными значениями - "?" (подставляемых параметров)
     *
     * @param Builder $query
     * @return null|string
     */
    protected static function getRawSql(Builder $query): ?string
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        foreach($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }

}
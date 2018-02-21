<?php

namespace App;

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
    public static function apply(Request $filters)
    {
        $query = (new User())->newQuery();

        switch ($filters->sort) {
            case 'rating':
                $query = static::orderByRatingDesc($query);
                break;
            case 'date':
                $query->orderByDesc('created_at');
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
    protected static function orderByRatingDesc(Builder $query)
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
        ;
    }

    /**
     * Получить sql запрос вместе с заполнеными значениями - "?"
     *
     * @param Builder $query
     * @return null|string
     */
    protected static function getRawSql(Builder $query)
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        foreach($bindings as $binding)
        {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }

}
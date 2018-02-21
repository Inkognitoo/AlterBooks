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
class BookSearch
{
    /**
     * Отфильтровать и отсортировать книги
     *
     * @param Request $filters
     * @return Builder
     */
    public static function apply(Request $filters)
    {
        $query = (new Book())->newQuery();

        if ($filters->genres) {
            foreach ($filters->genres as $genre) {
                $query->whereHas('genres', function ($genres) use ($genre) {
                    $genres->where(['slug' => $genre]);
                });
            }
        }

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
     * Сортировать книги по суммарному рейтингу их рецензий. От большего к меньшему
     *
     * @param Builder $query
     * @return Builder
     */
    protected static function orderByRatingDesc(Builder $query)
    {
        return $query->from((new Book())->getTable() . ' AS books')
            ->select(['books.*'])
            ->leftJoin((new Review())->getTable() . ' AS reviews', function ($reviews) {
                $reviews->on(['reviews.book_id' => 'books.id'])
                    ->whereNull('reviews.deleted_at');
            })
            ->whereNull('reviews.deleted_at')
            ->groupBy('books.id')
            ->orderByDesc(DB::raw('COALESCE(AVG(reviews.rating), 0)'))
            ->orderBy('books.created_at')
        ;
    }

}
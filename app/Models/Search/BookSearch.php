<?php

namespace App\Models\Search;

use App\Models\Book;
use App\Models\Review;
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
    public static function apply(Request $filters): Builder
    {
        $query = (new Book())->newQuery();
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
    protected static function applyFilters(Builder $query, Request $filters): Builder
    {
        if (filled($filters->genres)) {
            $query = static::filterByGenres($query, $filters->genres);
        }

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
            case 'date':
                $query = static::orderByDateDesc($query);
                break;
            default:
                $query = static::orderByRatingDesc($query);
                break;
        }

        return $query;
    }

    /**
     * Отфильтровать книги по наличию жанра/жанров
     *
     * @param Builder $query
     * @param array $genres
     * @return Builder
     */
    protected static function filterByGenres(Builder $query, array $genres): Builder
    {
        foreach ($genres as $genre) {
            $query->whereHas('genres', function ($genres) use ($genre) {
                $genres->where(['slug' => $genre]);
            });
        }

        return $query;
    }

    /**
     * Сортировать книги по суммарному рейтингу их рецензий. От большего к меньшему
     *
     * @param Builder $query
     * @return Builder
     */
    protected static function orderByRatingDesc(Builder $query): Builder
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
            ->orderBy('books.id')
        ;
    }

    /**
     * Сортировать книги по дате добавления. От новых к старым
     *
     * @param Builder $query
     * @return Builder
     */
    protected static function orderByDateDesc(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')
            ->orderBy('books.id')
        ;
    }

}
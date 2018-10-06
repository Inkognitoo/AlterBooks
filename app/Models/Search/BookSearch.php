<?php

namespace App\Models\Search;

use App\Http\Requests\Api\BookSearchRequest;
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
    protected const PER_PAGE = 15;

    /**
     * Отфильтровать и отсортировать книги
     *
     * @param BookSearchRequest $filters
     * @return SearchResult
     */
    public static function apply(BookSearchRequest $filters): SearchResult
    {
        $query = (new Book())->newQuery();

        $query = static::applyFilters($query, $filters);
        $query = static::applySort($query, $filters);
        [$query, $total] = static::applyPaginate($query, $filters);

        return static::wrapResult($query, $filters, $total);
    }

    /**
     * Поместить результат фильтрации в объект SearchResult
     *
     * @param $query
     * @param $filters
     * @param $total
     * @return SearchResult
     */
    protected static function wrapResult(Builder $query, BookSearchRequest $filters, int $total): SearchResult
    {
        $search_result = new SearchResult();

        $per_page = (int)$filters->perPage;
        if (blank($per_page) || $per_page <= 0) {
            $per_page = self::PER_PAGE;
        }
        $search_result->per_page = $per_page;

        $current_page = (int)$filters->currentPage;
        if (blank($current_page) || $current_page <= 0) {
            $current_page = 1;
        }
        $search_result->current_page = $current_page;

        $search_result->total = $total;
        $search_result->page_count = ceil($total / $search_result->per_page);

        $search_result->filtered = [
            'genres' => $filters->genres ?? [],
            'title' => 'test',
        ];

        switch ($filters->sort) {
            case 'rating':
                $search_result->sorted = [
                    'sort' => 'rating',
                    'direction' => 'asc',
                ];
                break;
            case 'date':
                $search_result->sorted = [
                    'sort' => 'date',
                    'direction' => 'asc',
                ];
                break;
            default:
                $search_result->sorted = [
                    'sort' => 'rating',
                    'direction' => 'asc',
                ];
                break;
        }

        $search_result->items = $query
            ->with('genres')
            ->with('author')
            ->get()
        ;

        return $search_result;
    }

    /**
     * Применить имеющиеся фильтры
     *
     * @param Builder $query
     * @param BookSearchRequest $filters
     * @return Builder
     */
    protected static function applyFilters(Builder $query, BookSearchRequest $filters): Builder
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
     * @param BookSearchRequest $filters
     * @return Builder
     */
    protected static function applySort(Builder $query, BookSearchRequest $filters): Builder
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
     * Применить пагинацию
     *
     * @param Builder $query
     * @param BookSearchRequest $filters
     * @return array
     */
    protected static function applyPaginate(Builder $query, BookSearchRequest $filters): array
    {
        $per_page = (int)$filters->perPage;
        if (blank($per_page) || $per_page <= 0) {
            $per_page = self::PER_PAGE;
        }

        $current_page = (int)$filters->currentPage;
        if (blank($current_page) || $current_page <= 0) {
            $current_page = 1;
        }

        // Ларавел неприятно удивляет и в случае, если в запросе есть group by, своим ->count()
        // возвращает количество групп, а не итоговых строк в выборке. По этому делаем так:
        $raw_query = 'SELECT COUNT(*) AS total FROM (' . $query->toSql() .') AS original_query';
        $count_query = DB::select($raw_query, $query->getBindings());
        $total = array_first($count_query)->total;

        $offset = $per_page * ($current_page - 1);

        return [
            $query
                ->offset($offset)
                ->limit($per_page),
            $total
        ];
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
        //TODO: очень мощный оверхэд. Нужно подумать, как это можно оптимизировать
        foreach ($genres as $genre) {
            $query->whereHas('genres', function ($genres) use ($genre) {
                /** @var Builder $genres*/
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
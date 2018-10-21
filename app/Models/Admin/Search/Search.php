<?php

namespace App\Models\Admin\Search;

use App\Models\Admin\Search\Helper\Filter;
use App\Models\Admin\Search\Helper\Paginate;
use App\Models\Admin\Search\Helper\Sort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Schema;

/**
 * Поисковый класс для работы JQueryDataTable
 *
 * @package App\Models\Admin\Search
 */
abstract class Search
{
    /**
     * @var string $search_class Класс модели, по которой производится поиск
     */
    protected $search_class;

    /**
     * @var string $collection_class Класс модели, по которой производится поиск
     */
    protected $collection_class;

    /**
     * @var string $resource_class Класс модели, по которой производится поиск
     */
    protected $resource_class;

    /**
     * @var array $text_search_fields Список полей, поиск по которым происходит через: <поле> like %<значение>%
     * Это применяется для полей хранящих в себе строковые данные
     */
    protected $text_search_fields = [];

    /**
     * @var array $date_search_fields Список полей, поиск по которым происходит через нахождение даты в промежутке
     * значений
     */
    protected $date_search_fields = [];

    /**
     * @var string $deleted_at_field Поле для проверки того факта, была ли удалена модель
     */
    protected $deleted_at_field = 'is_trashed';

    /**
     * @var int $items_count Общее количество сущностей
     */
    private $items_count;

    /**
     * @var int $filtered_items_count Общее количество сущностей после фильтрации
     */
    private $filtered_items_count;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Инициализация необходимых параметров
     *
     * @return void
     */
    abstract protected function init(): void;


    /**
     * Отфильтровать и отсортировать пользователей
     *
     * @param Request $request
     * @return ResourceCollection
     */
    final public function apply(Request $request): ResourceCollection
    {
        /** @var Builder $query */
        $query = (new $this->search_class)
            ->newQueryWithoutScopes()
        ;

        $filters = new Filter($request);
        $sort = new Sort($request);
        $paginate = new Paginate($request);

        $this->items_count = $query->count();
        $query = $this->applyFilters($query, $filters);
        $this->filtered_items_count = $query->count();

        $query = $this->applySort($query, $sort);
        $query = $this->paginate($query, $paginate);

        return $this->buildResponse($query);
    }

    /**
     * Конвертировать выборку в массив, валидный для JQueryTable
     *
     * @param Builder $query
     * @return ResourceCollection
     */
    private function buildResponse(Builder $query): ResourceCollection
    {
        $meta = [
            'recordsTotal' => $this->items_count,
            'recordsFiltered' => $this->filtered_items_count,
        ];

        return (new $this->collection_class($this->resource_class::collection($query->get())))
            ->additional($meta)
        ;
    }

    /**
     * Получить отфильтрованную выборку
     *
     * @param Builder $query
     * @param Filter $filters
     * @return Builder
     */
    private function applyFilters(Builder $query, Filter $filters): Builder
    {
        foreach ($filters->all() as $filter => $value) {
            if (\in_array($filter, $this->text_search_fields, true)) {
                $query = $this->defaultStringFilterBy($query, $filter, $value);
                continue;
            }

            if (\in_array($filter, $this->date_search_fields, true)) {
                $query = $this->defaultDateFilterBy($query, $filter, $value);
                continue;
            }

            if ($filter === $this->deleted_at_field) {
                $query = $this->defaultTrashedFilterBy($query, $value);
            }

            $method_name = 'filterBy' . ucfirst(camel_case($filter));
            if (method_exists($this, $method_name)) {
                $query = $this->$method_name($query, $value);
                continue;
            }

            if (Schema::hasColumn((new $this->search_class)->getTable(), $filter)) {
                $query = $this->defaultFilterBy($query, $filter, $value);
                continue;
            }
        }

        return $query;
    }

    /**
     * Получить отсортированную выборку
     *
     * @param Builder $query
     * @param Sort $sort
     * @return Builder
     */
    private function applySort(Builder $query, Sort $sort): Builder
    {
        $method_name = 'orderBy' . ucfirst(camel_case($sort->column));
        if (method_exists($this, $method_name)) {
            return $this->$method_name($query, $sort->direction);
        }

        if ($sort->column === 'is_trashed') {
            return $this->defaultTrashedOrderBy($query, $sort);
        }

        return $this->defaultOrderBy($query, $sort);
    }

    /**
     * Получить срез выборки
     *
     * @param Builder $query
     * @param Paginate $paginate
     * @return Builder
     */
    private function paginate(Builder $query, Paginate $paginate): Builder
    {
        return $query->offset($paginate->start)
            ->limit($paginate->length)
        ;
    }

    /**
     * Отфильтровать список сущностей по произвольному полю
     *
     * @param Builder $query
     * @param string $field Поле по которому производится поиск
     * @param mixed $value Значение поиска
     * @return Builder
     */
    private function defaultFilterBy(Builder $query, $field, $value): Builder
    {
        return $query->where([$field => $value]);
    }

    /**
     * Отфильтровать список сущностей по произвольному текстовому полю
     *
     * @param Builder $query
     * @param string $field Поле по которому производится поиск
     * @param mixed $value Значение поиска
     * @return Builder
     */
    private function defaultStringFilterBy(Builder $query, $field, $value): Builder
    {
        $value = \mb_strtolower($value);
        return $query->where(new Expression("LOWER({$field})"), 'like', "%{$value}%");
    }

    /**
     * Отфильтровать список сущностей между указанных дат
     *
     * @param Builder $query
     * @param $field
     * @param $value
     * @return Builder
     */
    private function defaultDateFilterBy(Builder $query, $field, $value): Builder
    {
        [$from, $to] = explode('|', $value);
        $from = new Carbon($from);
        $to = new Carbon($to);

        return $query->whereBetween($field, [$from, $to]);
    }

    /**
     * Отфильтровать список сущностей по признаку существования
     *
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    private function defaultTrashedFilterBy(Builder $query, $value): Builder
    {
        if ($value == 'true') {
            $query = $query->whereNotNull('deleted_at');
        } else {
            $query = $query->where(['deleted_at' => null]);
        }

        return $query;
    }

    /**
     * Отсортировать список сущностей по произвольному полю
     *
     * @param Builder $query
     * @param Sort $sort
     * @return Builder
     */
    private function defaultOrderBy(Builder $query, Sort $sort): Builder
    {
        return $query->orderBy($sort->column, $sort->direction);
    }

    /**
     * Отсортировать список сущностей по факту удаления
     *
     * @param Builder $query
     * @param Sort $sort
     * @return Builder
     */
    private function defaultTrashedOrderBy(Builder $query, Sort $sort): Builder
    {
        return $query->orderBy('deleted_at', $sort->direction);
    }
}
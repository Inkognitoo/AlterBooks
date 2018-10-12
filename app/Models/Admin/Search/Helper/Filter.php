<?php

namespace App\Models\Admin\Search\Helper;

use Illuminate\Http\Request;

/**
 * Класс хелпер для парсинга запроса от JQueryTables.
 * Используется для фильтрации запроса
 *
 * @package App\Models\Admin\Search\Helper
 */
class Filter
{
    /**
     * @var array Список фильтров
     */
    protected $filters = [];

    /**
     * Filter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $columns = $request->columns ?? [];
        foreach ($columns as $column) {
            if (!empty($column['data']) && !empty($column['search']['value'])) {
                $this->filters[$column['data']] = $column['search']['value'];
            }
        }
    }

    /**
     * Получить список всех фильтров
     *
     * @return array
     */
    public function all(): array
    {
        return $this->filters;
    }
}
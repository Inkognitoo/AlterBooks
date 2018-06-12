<?php

namespace App\Models\Admin\Search\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Класс хелпер для парсинга запроса от JQueryTables.
 * Используется для сортировки отфильтрованного запроса
 *
 * @package App\Models\Admin\Search\Helper
 *
 * @property-read string $column Столбец по которому будет происходить сортировка
 * @property-read string $direction Направление сортировки
 */
class Sort extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'column', 'direction',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'column' => 'string',
        'direction' => 'string',
    ];

    /**
     * @var string $column Столбец по которому будет происходить сортировка
     */
    protected $column = 'id';

    /**
     * @var string $direction Направление сортировки
     */
    protected $direction = 'asc';

    /**
     * Sort constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $column_index = $request['order'][0]['column'] ?? 0;
        $direction = $request['order'][0]['dir'] ?? $this->direction;
        $column = $request['columns'][$column_index]['name'] ?? $this->column;

        parent::__construct(['column' => $column, 'direction' => $direction]);
    }

    /**
     * Получить столбец по которому будет происходить сортировка
     *
     * @return string
     */
    public function getColumnAttribute(): string
    {
        return $this->column;
    }

    /**
     * Получить направление сортировки
     *
     * @return string
     */
    public function getDirectionAttribute(): string
    {
        return $this->direction;
    }

    /**
     * Указать столбец по которому будет происходить сортировка
     *
     * @param string $column
     */
    public function setColumnAttribute(string $column): void
    {
        $this->column = $column;
    }

    /**
     * Указать направление сортировки
     *
     * @param string $direction
     */
    public function setDirectionAttribute(string $direction): void
    {
        $this->direction = $direction;
    }
}
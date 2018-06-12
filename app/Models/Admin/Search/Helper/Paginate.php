<?php

namespace App\Models\Admin\Search\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Класс хелпер для парсинга запроса от JQueryTables.
 * Используется для пагинации отфильтрованного запроса
 *
 * @package App\Models\Admin\Search\Helper
 *
 * @property int $start Сущность начиная с которой необходимо получить выборку
 * @property int $length Количество сущностей в выборке
 */
class Paginate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start', 'length',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'int',
        'length' => 'int',
    ];

    /**
     * @var int $start Сущность начиная с которой необходимо получить выборку
     */
    protected $start = 0;

    /**
     * @var int $length Количество сущностей в выборке
     */
    protected $length = 5;

    /**
     * Paginate constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request->only(['start', 'length']));
    }

    /**
     * Получить сущность начиная с которой необходимо получить выборку
     *
     * @return int
     */
    public function getStartAttribute(): int
    {
        return $this->start;
    }

    /**
     * Получить количество сущностей в выборке
     *
     * @return int
     */
    public function getLengthAttribute(): int
    {
        return $this->length;
    }

    /**
     * Указать сущность начиная с которой необходимо получить выборку
     *
     * @param int $start
     */
    public function setStartAttribute($start): void
    {
        $this->start = $start;
    }

    /**
     * Указать количество сущностей в выборке
     *
     * @param int $length
     */
    public function setLengthAttribute($length): void
    {
        $this->length = $length;
    }

}
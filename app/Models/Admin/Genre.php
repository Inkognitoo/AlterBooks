<?php

namespace App\Models\Admin;

use App\Models\Genre as BaseGenre;
use App\Traits\Admin\Attributes;

/**
 * Class Genre
 *
 * @package App\Models\Admin
 */
class Genre extends BaseGenre
{
    use Attributes;

    /**
     * @var array $safe_attributes Список атрибутов для отображения
     */
    protected $safe_attributes = [
        'id', 'name', 'slug', 'created_at', 'updated_at',
    ];

    /**
     * @var array $disabled_edit_fields Список атрибутов, которым нужно отображать неактивные поля редактирования
     */
    protected $disabled_edit_fields = ['id', 'updated_at', 'created_at'];
}
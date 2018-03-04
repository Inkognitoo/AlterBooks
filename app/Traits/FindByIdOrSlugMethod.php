<?php

namespace App\Traits;
use Illuminate\Database\Query\Builder;

/**
 * Трейт для добавления метода поиска модели по id вида id[цифра], либо по slug
 *
 * Trait FindByIdOrSlugMethod
 * @package App\Traits
 */
trait FindByIdOrSlugMethod {

    /**
     * Вернуть модель по id вида id[цифра], либо по slug
     *
     * @param string $id Строка вида id[цифра] либо просто строка
     * @param string $slug_name Столбец для поиска по slug. По умолчанию значение константы SLUG_NAME либо "slug"
     * @return mixed
     */
    public static function findByIdOrSlug($id, $slug_name = null)
    {
        //Проверяем соотвествие на шаблон вида: id[цифра]
        if ((bool) preg_match('/(?<=^id)[\d]+$/', $id, $matches)) {
            list($id) = $matches;
            $model = self::find($id);
        } else {
            $slug_name = $slug_name ?? (defined('self::SLUG_NAME') ? self::SLUG_NAME : 'slug');
            $model = self::where([$slug_name => $id])
                ->first()
            ;
        }

        return $model;
    }

    /**
     * Вернуть модель по id вида id[цифра], либо по slug
     *
     * @param Builder $query
     * @param string $id Строка вида id[цифра] либо просто строка
     * @param string $slug_name Столбец для поиска по slug. По умолчанию значение константы SLUG_NAME либо "slug"
     * @return mixed
     */
    public function scopeFindByIdOrSlug($query, $id, $slug_name = null)
    {
        //Проверяем соотвествие на шаблон вида: id[цифра]
        if ((bool) preg_match('/(?<=^id)[\d]+$/', $id, $matches)) {
            list($id) = $matches;
            $query->where(['id' => $id]);
        } else {
            $class_name = get_class($query->getModel());
            $slug_name = $slug_name ?? (defined("{$class_name}::SLUG_NAME") ? self::SLUG_NAME : 'slug');
            $query->where([$slug_name => $id]);
        }

        return $query;
    }
}
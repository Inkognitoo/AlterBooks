<?php

namespace App\Traits;

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
}
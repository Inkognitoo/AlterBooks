<?php

namespace App\Scopes;

use \Illuminate\Database\Eloquent\Builder;

class BaseScope
{
    /**
     * Получить alias, если запрос книг был произведён с ним
     *
     * @param Builder $builder
     * @return null|string
     */
    protected function getAlias(Builder $builder)
    {
        $alias = null;
        $from = preg_split("/[Aa][sS]/", $builder->getQuery()->from);
        if (count($from) === 2) {
            $alias = trim($from[1]);
        }

        return $alias;
    }
}
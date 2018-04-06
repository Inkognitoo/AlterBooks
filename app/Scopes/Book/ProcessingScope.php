<?php

namespace App\Scopes\Book;

use App\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProcessingScope extends BaseScope implements Scope
{
    /**
     * Добавить обязательное условие по обработке (только обработанные книги)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $column = 'is_processing';

        $alias = $this->getAlias($builder);
        if (!empty($alias)) {
            $column = $alias . '.' . $column;
        }

        $builder->where($column, false);
    }
}
<?php

namespace App\Scopes;

use App\Models\Book;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StatusScope extends BaseScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $column = 'status';

        $alias = $this->getAlias($builder);
        if (!empty($alias)) {
            $column = $alias . '.' . $column;
        }

        $builder->where($column, Book::STATUS_OPEN);
    }
}
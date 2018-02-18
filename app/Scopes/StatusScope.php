<?php

namespace App\Scopes;

use App\Book;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StatusScope implements Scope
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

        // На случай если книгу запросили с алиасом
        $from = preg_split("/[Aa][sS]/", $builder->getQuery()->from);
        if (count($from) == 2) {
            $alias = trim($from[1]);
            $column = $alias . '.' . $column;
        }

        $builder->where($column, Book::STATUS_OPEN);
    }
}
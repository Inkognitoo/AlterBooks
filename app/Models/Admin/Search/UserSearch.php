<?php

namespace App\Models\Admin\Search;

use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Поисковая модель для пользователя
 *
 * Class UserSearch
 * @package App
 *
 */
class UserSearch extends Search
{
    /**
     * Инциализация всех необходимых переменных
     */
    public function init(): void
    {
        $this->search_class = User::class;
        $this->collection_class = UserCollection::class;
        $this->resource_class = UserResource::class;

        $this->text_search_fields = ['email', 'nickname'];
        $this->date_search_fields = ['created_at'];
    }

    /**
     * Получить список пользователей отфильтрованных по ФИО
     *
     * @param Builder $query
     * @param $full_name
     * @return Builder
     */
    public function filterByFullName(Builder $query, $full_name): Builder
    {
        $full_name = mb_strtolower($full_name);

        return $query->where(new Expression('LOWER(CONCAT(surname, name, patronymic))'), 'like', "%{$full_name}%");
    }

    /**
     * Получить выборку пользователей, отсортированную по ФИО
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function orderByFullName(Builder $query, $direction): Builder
    {
        return $query->orderBy(new Expression('CONCAT(surname, name, patronymic)'), $direction);
    }
}
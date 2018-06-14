<?php

namespace App\Models\Admin\Search;

use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;

/**
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

        $this->text_search_fields = ['name', 'email', 'surname', 'patronymic', 'nickname'];
        $this->date_search_fields = ['created_at'];
    }
}
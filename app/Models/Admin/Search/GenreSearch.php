<?php

namespace App\Models\Admin\Search;

use App\Http\Resources\Admin\GenreCollection;
use App\Http\Resources\Admin\GenreResource;
use App\Models\Genre;

/**
 * Поисковая модель жанра
 *
 * Class GenreSearch
 * @package App
 *
 */
class GenreSearch extends Search
{
    /**
     * Инициализация всех необходимых переменных
     */
    public function init(): void
    {
        $this->search_class = Genre::class;
        $this->collection_class = GenreCollection::class;
        $this->resource_class = GenreResource::class;

        $this->text_search_fields = ['name', 'slug'];
    }
}
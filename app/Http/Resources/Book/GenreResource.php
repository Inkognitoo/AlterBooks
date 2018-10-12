<?php

namespace App\Http\Resources\Book;

use App\Models\Genre;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class GenreResource
 * @package App\Http\Resources\Book
 * @mixin Genre
 */
class GenreResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}

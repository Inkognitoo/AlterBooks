<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin\Genre;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class GenreResource
 * @package App\Http\Resources\Admin
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
            'is_trashed' => $this->trashed(),
            'created_at' => $this->created_at,
            'show_url' => route('genre.show', ['id' => $this->id]),
            'edit_url' => route('genre.edit.show', ['id' => $this->id]),
        ];
    }
}

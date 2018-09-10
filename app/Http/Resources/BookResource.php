<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Http\Resources\Book\UserResource;
use App\Http\Resources\Book\GenreResource;
use App\Http\Resources\Book\ReviewResource;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class BookResource
 * @package App\Http\Resources
 * @mixin Book
 */
class BookResource extends Resource
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
            'title' => $this->title,
            'description' => $this->description_plain,
            'rating' => $this->rating,
            'publishDate' => $this->created_at,
            'pageCount' => $this->page_count,
            'url' => $this->url,
            'author' => new UserResource($this->author),
            'genres' => [
                'data' => GenreResource::collection($this->genres),
                'total' => count($this->genres),
            ],
            'reviews' => [
                'data' => ReviewResource::collection($this->reviews),
                'total' => count($this->reviews),
            ],
        ];
    }
}

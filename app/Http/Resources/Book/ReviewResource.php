<?php

namespace App\Http\Resources\Book;

use App\Models\Review;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ReviewResource
 * @package App\Http\Resources\Book
 * @mixin Review
 */
class ReviewResource extends Resource
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
            'url' => '#',
        ];
    }
}

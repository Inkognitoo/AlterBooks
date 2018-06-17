<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

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
            'rating' => $this->rating,
            'author' => $this->user->full_name,
            'book' => $this->book->title,
            'created_at' => $this->created_at,
            'show_url' => route('review.show', ['id' => $this->id]),
            'edit_url' => '',
        ];
    }
}

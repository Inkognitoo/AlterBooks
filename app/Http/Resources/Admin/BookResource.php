<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

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
            'author' => $this->author->full_name,
            'status' => $this->status,
            'show_url' => route('book.show', ['id' => $this->id]),
            'edit_url' => route('book.edit.show', ['id' => $this->id]),
        ];
    }
}

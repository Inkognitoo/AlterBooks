<?php

namespace App\Http\Resources\Book;

use App\Models\User;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserResource
 * @package App\Http\Resources\Book
 * @mixin User
 */
class UserResource extends Resource
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
            'fullName' => $this->full_name,
            'url' => $this->url,
        ];
    }
}

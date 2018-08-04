<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserResource
 * @package App\Http\Resources
 *
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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar(60, 60),
            'profile_url' => route('user.show', ['id' => $this->id]),
        ];
    }
}

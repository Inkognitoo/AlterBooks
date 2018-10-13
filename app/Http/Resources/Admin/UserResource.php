<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin\User;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserResource
 * @package App\Http\Resources\Admin
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
            'full_name' => $this->surname . ' ' . $this->name . ' ' . $this->patronymic,
            'nickname' => $this->nickname,
            'gender' => $this->gender,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'is_trashed' => $this->trashed(),
            'show_url' => route('user.show', ['id' => $this->id]),
            'edit_url' => route('user.edit.show', ['id' => $this->id]),
        ];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App
 *
 * @property integer $id User id
 * @property string $name User name
 * @property string $surname User surname
 * @property string $patronymic User patronymic
 */
class Profile extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

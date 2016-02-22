<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function validate($request)
    {
        $v = Validator::make($request, $this->rules);

        if ($v->fails())
        {
            $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'patronymic', 'photo', 'birthday'
    ];

    //TODO: разобраться с правилами валидирования даты
    private $rules = [
        'name' => 'max:255',
        'surname' => 'max:255',
        'patronymic' => 'max:255',
        'birthday' => 'date'
    ];

    private $errors;
}

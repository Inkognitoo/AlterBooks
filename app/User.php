<?php

namespace App;
use Validator;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function book()
    {
        return $this->hasMany('App\Book', 'user_id', 'author_id');
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
        'nickname', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    private $rules = [
        'nickname' => 'required|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:6',
        'password_confirmation' => 'required|min:6'
    ];

    private $errors;

}

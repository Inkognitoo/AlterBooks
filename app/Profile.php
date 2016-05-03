<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
/**
 * App\Profile
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $photo
 * @property string $birthday
 * @property string $gender
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereSurname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile wherePatronymic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile wherePhoto($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Profile whereUpdatedAt($value)
 */
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
        'name', 'surname', 'patronymic', 'birthday', 'gender'
    ];

    //TODO: regexp ограничение для ника
    /*
     * не явялется одним из списка зарезервированных
     * не id[number]
     * содержит только цифры, буквы, - и _
     */
    private $rules = [
        'nickname' => 'min:1|max:255|unique:users',
        'name' => 'min:1|max:255',
        'surname' => 'min:1|max:255',
        'patronymic' => 'min:1|max:255',
        'birthday' => 'date_format:"Y-m-d"',
        'gender' => ['regex:/^((man)|(woman))$/i']
    ];

    private $errors;
}

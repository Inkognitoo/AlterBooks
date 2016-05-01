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

    //TODO: разобраться с правилами валидирования даты
    //date_format:format
    private $rules = [
        'name' => 'max:255',
        'surname' => 'max:255',
        'patronymic' => 'max:255',
        'birthday' => 'date',
        'gender' => ['regex:/^((man)|(woman))$/i']
    ];

    private $errors;
}

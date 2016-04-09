<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
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
	class Profile extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $nickname
 * @property string $email
 * @property string $password
 * @property string $reset_code
 * @property boolean $email_verify
 * @property string $email_verify_code
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Profile $profile
 * @property-read \App\Oauth $oauth
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $book
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereResetCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmailVerify($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmailVerifyCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Book
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $cover
 * @property integer $rars_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $author
 * @property-read \App\Rars $rars
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereCover($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereRarsId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book whereUpdatedAt($value)
 */
	class Book extends \Eloquent {}
}

namespace App{
/**
 * App\Oauth
 *
 * @property integer $id
 * @property string $provider
 * @property string $oauth_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereOauthId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Oauth whereUpdatedAt($value)
 */
	class Oauth extends \Eloquent {}
}

namespace App{
/**
 * App\Rars
 *
 * @property integer $id
 * @property string $name
 * @property string $eternal_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 * @method static \Illuminate\Database\Query\Builder|\App\Rars whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rars whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rars whereEternalName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rars whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rars whereUpdatedAt($value)
 */
	class Rars extends \Eloquent {}
}


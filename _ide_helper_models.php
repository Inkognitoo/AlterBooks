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
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 */
	class Profile {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $nickname
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Profile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $book
 */
	class User {}
}

namespace App{
/**
 * App\Book
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $cover
 * @property integer $author_id
 * @property integer $rars_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $author
 * @property-read \App\Rars $rars
 */
	class Book {}
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
 */
	class Rars {}
}


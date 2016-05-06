<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Log;
use Validator;
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
class Oauth extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    static function serializeForSession($social_user, $provider)
    {
        $serialized_social_user = [];
        switch ($provider) {
            case 'google':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->user['name']['givenName'];
                $serialized_social_user['surname'] = $social_user->user['name']['familyName'];
                $serialized_social_user['gender'] = $social_user->user['gender'];
                //TODO: распарсить для подходящего размера автара
                //TODO: загрузка аватара
                $serialized_social_user['avatar'] = $social_user->avatar;

                return $serialized_social_user;
                break;
            case 'twitter':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->name;
                //TODO: загрузка аватара
                $serialized_social_user['avatar'] = $social_user->avatar_original;

                return $serialized_social_user;
                break;
            case 'facebook':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->user['name'];
                $serialized_social_user['gender'] = $social_user->user['gender'];
                //TODO: загрузка аватара
                $serialized_social_user['avatar'] = $social_user->avatar_original;

                return $serialized_social_user;
                break;
            case 'vkontakte':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->user['first_name'];
                $serialized_social_user['surname'] = $social_user->user['last_name'];
                //TODO: запрашивать оригильный размер аватары пользователя
                //см. vk api https://vk.com/dev/users.get
                //TODO: загрузка аватара
                $serialized_social_user['avatar'] = $social_user->avatar;

                return $serialized_social_user;
                break;
            default:
                Log::error('Неизвестный провайдер в serializeForSession: '.$provider);
                return false;
        }
    }

    public function createUser($social_user)
    {
        switch ($social_user['provider']) {
            case 'google':
                return $this->createWithGoogle($social_user);
                break;
            case 'twitter':
                return $this->createWithTwitter($social_user);
                break;
            case 'facebook':
                return $this->createWithFacebook($social_user);
                break;
            case 'vkontakte':
                return $this->createWithVkontakte($social_user);
                break;
            default:
                Log::error('Неизвестный провайдер в createUser: '.$social_user['provider']);
                return false;
        }
    }

    public function validate($request)
    {
        $v = Validator::make($request, $this->rules);

        if ($v->fails())
        {
            array_push($this->errors, $v->errors());
            return false;
        }

        return true;
    }

    public function validateNickname($request)
    {
        $v = Validator::make($request, $this->rulesNickname);

        if ($v->fails())
        {
            array_push($this->errors, $v->errors());
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    private $rules = [
        'email' => 'required|email|max:255|unique:users'
    ];

    private $rulesNickname = [
        'nickname' => 'min:1|max:20|unique:users|not_id|not_reserved',
    ];

    private $errors;

    //TODO: один, универсальный метод для создания пользователя
    private function createWithGoogle($social_user)
    {
        $user = new User();

        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
        $user->email_verify_code = bcrypt(Str::random(32));
        $user->email_change_code = bcrypt(Str::random(32));
        $user->save();

        if ($this->validateNickname(['nickname' => $social_user['nickname']])) {
            $user->nickname = $social_user['nickname'];
        } else {
            $user->nickname = "id{$user->id}";
        }
        $user->save();

        $profile = new Profile();
        $profile->name = $social_user['name'];
        $profile->surname = $social_user['surname'];
        $profile->gender = $social_user['gender'];
        $user->profile()->save($profile);
        //TODO: локально сохранять аватар ($social_user['photo'])

        return $user;
    }

    private function createWithTwitter($social_user)
    {
        $user = new User();
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
        $user->email_verify_code = bcrypt(Str::random(32));
        $user->email_change_code = bcrypt(Str::random(32));
        $user->save();

        if ($this->validateNickname(['nickname' => $social_user['nickname']])) {
            $user->nickname = $social_user['nickname'];
        } else {
            $user->nickname = "id{$user->id}";
        }
        $user->save();

        $profile = new Profile();
        $profile->name = $social_user['name'];
        $user->profile()->save($profile);
        //TODO: локально сохранять аватар ($social_user['photo'])

        return $user;
    }

    private function createWithFacebook($social_user)
    {
        $user = new User();
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
        $user->email_verify_code = bcrypt(Str::random(32));
        $user->email_change_code = bcrypt(Str::random(32));
        $user->save();

        if ($this->validateNickname(['nickname' => $social_user['nickname']])) {
            $user->nickname = $social_user['nickname'];
        } else {
            $user->nickname = "id{$user->id}";
        }
        $user->save();

        $profile = new Profile();
        $profile->name = $social_user['name'];
        $profile->gender = $social_user['gender'];
        $user->profile()->save($profile);
        //TODO: локально сохранять аватар ($social_user['photo'])

        return $user;
    }

    private function createWithVkontakte($social_user)
    {
        $user = new User();
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
        $user->email_verify_code = bcrypt(Str::random(32));
        $user->email_change_code = bcrypt(Str::random(32));
        $user->save();

        if ($this->validateNickname(['nickname' => $social_user['nickname']])) {
            $user->nickname = $social_user['nickname'];
        } else {
            $user->nickname = "id{$user->id}";
        }
        $user->save();

        $profile = new Profile();
        $profile->name = $social_user['name'];
        $profile->surname = $social_user['surname'];
        $user->profile()->save($profile);
        //TODO: локально сохранять аватар ($social_user['photo'])

        return $user;
    }
}

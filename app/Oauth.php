<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Log;
use Validator;

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
                $serialized_social_user['photo'] = $social_user->avatar;

                return $serialized_social_user;
                break;
            case 'twitter':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->name;
                $serialized_social_user['photo'] = $social_user->avatar_original;

                return $serialized_social_user;
                break;
            case 'facebook':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->user['name'];
                $serialized_social_user['gender'] = $social_user->user['gender'];
                $serialized_social_user['photo'] = $social_user->avatar_original;

                return $serialized_social_user;
                break;
            case 'vkontakte':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->user['first_name'];
                $serialized_social_user['surname'] = $social_user->user['last_name'];
                $serialized_social_user['photo'] = $social_user->avatar;

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
            $this->errors = $v->errors();
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

    private $errors;

    private function createWithGoogle($social_user)
    {
        $user = new User();
        $user->nickname = $social_user['nickname'];
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
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
        $user->nickname = $social_user['nickname'];
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
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
        $user->nickname = $social_user['nickname'];
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
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
        $user->nickname = $social_user['nickname'];
        $user->email = $social_user['email'];
        $user->password = bcrypt(Str::random(32));
        $user->save();

        $profile = new Profile();
        $profile->name = $social_user['name'];
        $profile->surname = $social_user['surname'];
        $user->profile()->save($profile);
        //TODO: локально сохранять аватар ($social_user['photo'])

        return $user;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Log;
use Validator;
use Illuminate\Http\UploadedFile;
use Storage;
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
                $serialized_social_user['avatar'] = preg_replace('/\?sz=50/', '?sz=200', $social_user->avatar);

                return $serialized_social_user;
                break;
            case 'twitter':
                $serialized_social_user['provider'] = $provider;
                $serialized_social_user['oauth_id'] = $social_user->id;
                $serialized_social_user['nickname'] = $social_user->nickname;
                $serialized_social_user['email'] = $social_user->email;
                $serialized_social_user['name'] = $social_user->name;
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
                $response = file_get_contents("https://api.vk.com/method/users.get?user_ids={$social_user->id}&fields=photo_200&version=5.52");
                $json = json_decode($response, true);
                $serialized_social_user['avatar'] = $json['response'][0]['photo_200'];

                return $serialized_social_user;
                break;
            default:
                Log::error('Неизвестный провайдер в serializeForSession: '.$provider);
                return false;
        }
    }

    public function createUser($social_user)
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
        $profile = new Profile($social_user);
        $user->profile()->save($profile);

        $this->oauth_id = $social_user['oauth_id'];
        $this->provider = $social_user['provider'];
        $user->oauth()->save($this);

        //TODO: локально сохранять аватар ($social_user['photo'])
        //если нужно, создаём необходимую директорию
        Storage::makeDirectory("avatars/{$user->id}");
        $avatar_name = Str::random(32);
        $tmp_avatar_name = storage_path("app/avatars/{$user->id}/").$avatar_name;
        file_put_contents($tmp_avatar_name, fopen($social_user['avatar'], 'r'));
        //TODO: найти какой-то более верный способ для этого
        $avatar = new UploadedFile($tmp_avatar_name, $avatar_name);
        //сохраняем новый аватар
        $new_avatar_name = $avatar_name.'.'.$avatar->guessExtension();
        Storage::move("avatars/{$user->id}/{$avatar_name}", "avatars/{$user->id}/{$new_avatar_name}");
        $user->profile->avatar = $new_avatar_name;
        $user->profile->save();

        return $user;
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
        'nickname' => 'required|max:20|unique:users|not_id|not_reserved',
    ];

    private $errors = [];
}

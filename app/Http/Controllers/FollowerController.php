<?php

namespace App\Http\Controllers;

use App\Follower;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Mail;

class FollowerController extends Controller
{
    protected $email = null;

    public function newFollower(Request $request)
    {
        $email = Str::lower($request->email);
        $followers = Follower::where('email', $email)->get();
        // пользователя ещё нет в базе?
        if ($followers->count() === 0) {
            // да
            $follower = new Follower();
            $follower->email = $email;
            $follower->unsubscribe_code = Str::random(20);
            $follower->save();

            // посылаем письмо
            $this->email = $follower->email;
            Mail::queue('emails.follow',
                [
                    'email' => $follower->email,
                    'unsubscribe_code' => $follower->unsubscribe_code
                ],
            function($message)
            {
                $message->to($this->email)->subject('Спасибо!');
            });

            return $this->buildResponse(['text' => 'Поздравляем Вас с подпиской на новостную рассылку AlterBooks!', 'unique' => true], 200);
        }

        // нет
        // пользователь подписан?
        if ((bool)$followers->first()->follow === true ) {
            // да
            return $this->buildResponse(['text' => 'Данный адрес уже включен в рассылку. Пожалуйста, проверьте адрес на наличие ошибок.'], 409);
        }

        // нет
        $followers->first()->follow = true;
        $followers->first()->unsubscribe_code = Str::random(20);
        $followers->first()->save();

        return $this->buildResponse(['text' => 'И снова рады приветствовать Вас! Подписка на новостную рассылку проекта AlterBooks прошла успешно.', 'unique' => false], 200);

    }

    public function unsubscribe(Request $request)
    {
        // Проверяем наличие кода в базе, отписываем соответсвующего пользователя
        $followers = Follower::where('email', $request->email)->where('unsubscribe_code', $request->code)->get();
        if ($followers->count() === 0) {
            return redirect('/');
        }

        $followers->first()->follow = false;
        $followers->first()->unsubscribe_code = '';
        $followers->first()->save();

        return view('unsubscribe', ['email' => $request->email]);
    }

    protected function buildResponse($property, $code)
    {
        switch($code){
            case 200:
                $json['status'] = 'success';
                $json['code'] = $code;
                break;
            default:
                $json['status'] = 'error';
                $json['code'] = $code;

        }

        $json['property'] = $property;

        return response(json_encode($json), $code)
            ->header('Content-Type', 'text/json');
    }
}

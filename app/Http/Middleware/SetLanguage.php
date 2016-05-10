<?php

namespace App\Http\Middleware;

use Closure;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //сначала проверяем куку
        if (array_key_exists('locale', $_COOKIE) && in_array($_COOKIE['locale'], config('custom.languages'))) {
            \App::setLocale($_COOKIE['locale']);
        } else {
            $this->setLanguageByBrowser();
        }

        return $next($request);
    }

    //Установка предпочитаемого пользователем языка (определяем через браузер)
    private function setLanguageByBrowser() {
        //массив имеющихся языков на сайте, и их соответствия
        $siteLanguage = array(
            //для языков ru, uk, be ... отображать ru
            'ru' => ['ru', 'uk', 'be', 'ky', 'ab', 'mo', 'et', 'lv'],
            'en' => ['en'],
        );

        if (($list = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']))) {
            if (preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)) {
                $language = array_combine($list[1], $list[2]);

                foreach ($language as $n => $v) {
                    $language[$n] = $v ? $v : 1;
                }

                arsort($language, SORT_NUMERIC);

                foreach ($language as $value => $key) {
                    $l = strtok($value, '-');
                    foreach ($siteLanguage as $siteLanguageValue) {
                        if (in_array($l, $siteLanguageValue)) {
                            //Проверяем наличие языка в нашей языковой базе, если есть ― подключаем
                            if (in_array($siteLanguageValue[0], config('custom.languages'))) {
                                \App::setLocale($siteLanguageValue[0]);
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }
}

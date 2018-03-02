<?php

use Illuminate\Support\Str;

if (!function_exists('t')) {
    /**
     * Перевести текущий текст
     *
     * @param  string  $key
     * @param  string  $text
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    function t($key, $text, $replace = [], $locale = null)
    {
        if (!app('translator')->has($key . '.' . $text, $locale)) {
            foreach ($replace as $key => $value) {
                $text = str_replace(
                    [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                    [$value, Str::upper($value), Str::ucfirst($value)],
                    $text
                );
            }
            return $text;
        }

        $translate_text = app('translator')->trans($key . '.' . $text, $replace, $locale);

        if (blank($translate_text)) {
            foreach ($replace as $key => $value) {
                $text = str_replace(
                    [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                    [$value, Str::upper($value), Str::ucfirst($value)],
                    $text
                );
            }
            return $text;
        }

        return $translate_text;
    }
}



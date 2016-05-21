<?php
namespace App;
use Illuminate\Validation\Validator;
use App\Language;

class CustomValidator extends Validator {

    public function validateNotId($attribute, $value, $parameters)
    {
        return !preg_match('/^id[0-9]+$/', $value);
    }

    public function validateNotReserved($attribute, $value, $parameters)
    {
        return !in_array($value, config('custom.reserved_pages'));
    }

    public function validateLanguage($attribute, $value, $parameters)
    {
        $languages = Language::all()->map(function ($language) {
            return $language->internal_name;
        })->toArray();

        return in_array($value, $languages);
    }
}
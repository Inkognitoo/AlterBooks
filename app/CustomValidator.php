<?php
namespace App;
use Illuminate\Validation\Validator;
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
        return in_array($value, config('custom.languages'));
    }
}
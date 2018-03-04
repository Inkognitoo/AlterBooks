<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Nickname implements Rule
{
    protected $attribute;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        return (bool) preg_match_all('/^[A-z\d-_]+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.nickname', ['attribute' => $this->attribute]);
    }
}

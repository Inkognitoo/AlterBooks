<?php

namespace App\Exceptions;


use Throwable;

class ValidateException extends ApiException
{
    /** @var string Переменная для хранения имени ошибки */
    private $name;

    public function __construct($name = '', $message = '', $code = 400, Throwable $previous = null)
    {
        if (blank($message)) {
            $message = 'Validate error. Please check your data carefully';
        }

        $this->name = $name;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Получаем имя ошибки
     *
     * return string
    */
    public function getName()
    {
        return $this->name;
    }
}

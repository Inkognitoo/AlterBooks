<?php

namespace App\Exceptions;


use Exception;

class ApiException extends Exception
{
    /** @var string Константа для указания того факта, что в реквесте произошла ошибка */
    public const ERROR_HEADER_LABEL = 'X-Error-Some-Random-String-For-Not-To-Be-In-The-User-Request';

}
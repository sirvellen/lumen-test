<?php
namespace App\Http\Services\Token\Exceptions;


use Exception;

class CreateTokenException extends Exception
{
    protected $message = 'Не удалось создать токен';
}

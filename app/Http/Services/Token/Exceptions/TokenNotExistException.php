<?php
namespace App\Http\Services\Token\Exceptions;


use Exception;

class TokenNotExistException extends Exception
{
    protected $message = 'Токена не существует';
}

<?php
namespace App\Http\Services\Token\Exceptions;


use Exception;

class CheckTokenException extends Exception
{
    protected $message = 'Невалидный токен';
}

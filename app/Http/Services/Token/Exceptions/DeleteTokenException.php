<?php
namespace App\Http\Services\Token\Exceptions;


use Exception;

class DeleteTokenException extends Exception
{
    protected $message = 'Не удалось удалить токен';
}

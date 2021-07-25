<?php
namespace App\Http\Validators;


abstract class BaseValidator
{
    /**
     * Правила валидации
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Сообщения об ошибках валидации
     *
     * @return array
     */
    abstract public function messages(): array;
}

<?php
namespace App\Http\Validators;


class UserRegisterValidator extends BaseValidator
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string'
        ];
    }

    /**
     * Сообщения об ошибках валидации
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Поле name должно быть заполнено',
            'name.string'   => 'Поле name должно быть строкой',

            'email.required' => 'Поле email должно быть заполнено',
            'email.email'    => 'Поле email должно быть почтовым адресом',
            'email.unique'   => 'Такой почтовый адрес уже зарегистрирован',

            'password.required' => 'Поле password должно быть заполнено',
            'password.string'   => 'Поле password должно быть строкой',
        ];
    }
}

<?php
namespace App\Http\Validators;


class UserLoginValidator extends BaseValidator
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
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
            'email.required' => 'Поле email должно быть заполнено',
            'email.email'    => 'Поле email должно быть почтовым адресом',

            'password.required' => 'Поле password должно быть заполнено',
            'password.string'   => 'Поле password должно быть строкой',
        ];
    }
}

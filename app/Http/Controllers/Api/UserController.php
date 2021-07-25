<?php
namespace App\Http\Controllers\Api;

use App\Http\Validators\UserRegisterValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Возвращает данные пользователя
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseError('Пользователя с таким ID не существует', 400);
        }

        return $this->responseSuccess($user->toArray());
    }

    /**
     * Регистрирует пользователя в системе
     *
     * @param Request $request
     * @param UserRegisterValidator $validator
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request, UserRegisterValidator $validator): JsonResponse
    {
        $this->validate($request, $validator->rules(), $validator->messages());

        $user           = new User();
        $user->name     = $request->get('name');
        $user->email    = $request->get('email');
        $user->password = Hash::make($request->get('password'));

        if (!$user->save()) {
            return $this->responseError('Не удалось зарегистрировать пользователя. Попробуйте ещё раз', 500);
        }

        return $this->responseSuccess(['message' => 'Успешная регистрация'], 201);
    }


}

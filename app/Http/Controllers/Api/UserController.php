<?php
namespace App\Http\Controllers\Api;

use App\Http\Services\Token\Exceptions\CheckTokenException;
use App\Http\Services\Token\Exceptions\CreateTokenException;
use App\Http\Services\Token\Exceptions\DeleteTokenException;
use App\Http\Services\Token\Exceptions\TokenNotExistException;
use App\Http\Services\Token\TokenService;
use App\Http\Validators\UserLoginValidator;
use App\Http\Validators\UserRegisterValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    /**
     * Сервис для работы с токенами
     *
     * @var TokenService
     */
    private $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Возвращает данные пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

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

    /**
     * Авторизует пользователя в системе
     *
     * @param Request $request
     * @param UserLoginValidator $validator
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request, UserLoginValidator $validator): JsonResponse
    {
        $this->validate($request, $validator->rules(), $validator->messages());

        /** @var User $user */
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return $this->responseError('Неверный адрес Эл. почты или пароль', 412);
        }

        try {
            $token = $this->tokenService->create($user);
        } catch (CreateTokenException $e) {
            return $this->responseError('Ошибка сервера. Попробуйте ещё раз', 500);
        }

        return $this->responseSuccess([
            'token' => $token,
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Выход пользователя из системы
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $this->tokenService->delete($user, $request->bearerToken());
        } catch (DeleteTokenException $e) {
            return $this->responseError('Ошибка сервера. Попробуйте ещё раз', 500);
        } catch (TokenNotExistException $e) {
            return $this->responseError($e->getMessage(), 400);
        }

        return $this->responseSuccess(['message' => 'Токен успешно удалён']);
    }

    /**
     * Обновляет токен у пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $token = $this->tokenService->refresh($request->user(), $request->bearerToken());
        } catch (CheckTokenException | TokenNotExistException $e) {
            return $this->responseError($e->getMessage(), 400);
        } catch (CreateTokenException | DeleteTokenException $e) {
            return $this->responseError('Ошибка сервера. Попробуйте ещё раз.', 500);
        }

        return $this->responseSuccess([
            'token' => $token,
            'message' => 'Успешно обновлено'
        ]);
    }
}

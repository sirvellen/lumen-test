<?php
namespace App\Http\Services\Token;


use App\Http\Services\Token\Exceptions\CheckTokenException;
use App\Http\Services\Token\Exceptions\CreateTokenException;
use App\Http\Services\Token\Exceptions\DeleteTokenException;
use App\Http\Services\Token\Exceptions\TokenNotExistException;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Генерирует токен
     *
     * @param User $user
     * @return string
     * @throws CreateTokenException
     */
    public function create(User $user): string
    {
        $token          = new Token();
        $token->token   = str_replace(["/", "?", "&", ".", "_", "\\", "#", '$'], "", Hash::make(Str::random(30)));
        $token->user_id = $user->id;

        if (!$token->save()) {
            throw new CreateTokenException();
        }

        return $token->token;
    }

    /**
     * Удаляет токен из базы
     *
     * @param User $user
     * @param string $userToken
     * @return bool
     * @throws DeleteTokenException
     * @throws TokenNotExistException
     */
    public function delete(User $user, string $userToken): bool
    {
        $token = Token::query()
            ->where('user_id', '=', $user->id)
            ->where('token', '=', $userToken)
            ->first();

        if (!$token) {
            throw new TokenNotExistException();
        }

        if (!$token->delete()) {
            throw new DeleteTokenException();
        }

        return true;
    }

    /**
     * Проверяет существование токена
     *
     * @param string $token
     * @return bool
     */
    public function exist(string $token): bool
    {
        return Token::query()->where('token', '=', $token)->exists();
    }

    /**
     * Проверяет соответствие токена пользователю
     *
     * @param User $user
     * @param string $userToken
     * @return bool
     */
    public function check(User $user, string $userToken): bool
    {
        return Token::query()
            ->where('user_id', '=', $user->id)
            ->where('token', '=', $userToken)
            ->exists();
    }

    /**
     * Обновляет токен пользователя
     *
     * @param User $user
     * @param string $userToken
     * @return string
     * @throws CheckTokenException
     * @throws CreateTokenException
     * @throws DeleteTokenException
     * @throws TokenNotExistException
     */
    public function refresh(User $user, string $userToken): string
    {
        if (!$this->check($user, $userToken)) {
            throw new CheckTokenException();
        }

        $token = $this->create($user);

        $this->delete($user, $userToken);

        return $token;
    }

    /**
     * Получить пользователя по токену
     *
     * @param string $userToken
     * @return User
     * @throws CheckTokenException
     */
    public function getUser(string $userToken): User
    {
        if (!$this->exist($userToken)) {
            throw new CheckTokenException();
        }

        /** @var Token $token */
        $token = Token::query()->where('token', '=', $userToken)->first();

        return $token->user;
    }
}

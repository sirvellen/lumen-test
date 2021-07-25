<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель токена авторизации
 *
 * @property int $id Идентификатор
 * @property int $user_id ID пользователя
 * @property string $token Токен
 * @property Carbon $created_at Время создания
 * @property Carbon $updated_at Время последнего редактирования
 * @property Carbon $last_use Время последнего использования
 * @property User $user Модель пользователя по токену
 *
 * Class Token
 * @package App\Models
 */
class Token extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token', 'last_use'
    ];

    /**
     * Пользователь которому принадлежит токен
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

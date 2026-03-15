<?php

declare(strict_types=1);

namespace App\Domains\Chat\Models;

use App\Domains\Message\Models\Message;
use App\Infrastructure\Models\Model;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $first_id
 * @property int $second_id
 * @property ?Carbon $last_message_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 *
 * @property User $first
 * @property User $second
 */
class Chat extends Model
{
    use Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected array $dates = ['last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function first(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_id');
    }

    public function second(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function lastChatMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'chat_id')->latestOfMany();
    }

    public function getOtherParticipantId(int $senderId): int {
        return $this->first_id === $senderId ? $this->second_id : $this->first_id;
    }

    public function hasUser(int $userId): bool
    {
        return $this->first_id === $userId || $this->second_id === $userId;
    }

    public static function betweenUsers(int $userId1, int $userId2)
    {
        return self::where(function ($q) use ($userId1, $userId2) {
            $q->where('first_id', $userId1)->where('second_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('first_id', $userId2)->where('second_id', $userId1);
        })->first();
    }
}

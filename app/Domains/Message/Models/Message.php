<?php

declare(strict_types=1);

namespace App\Domains\Message\Models;

use App\Domains\Chat\Models\Chat;
use App\Infrastructure\Models\Model;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $chat_id
 * @property int $sender_id
 * @property string $content
 * @property bool $is_read
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 *
 * @property Chat $chat
 * @property User $sender
 */
class Message extends Model
{
    use Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

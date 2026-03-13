<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{SoftDeletes};
use App\Infrastructure\Enums\OpenAI\OpenAIStatusEnum;

/**
 * @property int $id
 * @property string $taggable_type
 * @property int $taggable_id
 * @property OpenAIStatusEnum $status
 * @property ?string $raw_response
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class OpenAICall extends Model
{
    use SoftDeletes;

    protected $table = 'openai_calls';

    protected $fillable = ['taggable_type', 'taggable_id', 'status', 'raw_response'];

    protected $casts = [
        'status' => OpenAIStatusEnum::class,
    ];
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Carbon\Carbon;
use App\Infrastructure\Enums\Partition\PartitionRangeTypeEnum;

/**
 * @property int $id
 * @property string $table
 * @property PartitionRangeTypeEnum $range_type
 * @property Carbon $create_new_partition_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class PartitionTable extends Model
{
    protected $table = 'partition_tables';

    protected $fillable = ['table', 'range_type', 'create_new_partition_at'];

    protected $casts = [
        'range_type' => PartitionRangeTypeEnum::class,
        'create_new_partition_at' => 'datetime',
    ];
}

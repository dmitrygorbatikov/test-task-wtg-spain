<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class CarbonFromTimestampCast implements Cast
{
    private Carbon $minDate;
    private Carbon $maxDate;

    public function __construct()
    {
        $this->minDate = Carbon::parse('0000-01-01 00:00:01');
        $this->maxDate = Carbon::parse('9999-12-31 23:59:59');
    }

    /**
     * @throws ValidationException
     */
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): mixed {
        if (!is_numeric($value)) {
            throw ValidationException::withMessages([
                $property->name => ['Timestamp must be numeric.'],
            ]);
        }

        if ($value < $this->minDate->timestamp) {
            $this->throwValidateAfterError($property);
        }

        if ($value > $this->maxDate->timestamp) {
            $this->throwValidateBeforeError($property);
        }

        return Carbon::createFromTimestamp((int) $value);
    }

    /**
     * @throws ValidationException
     */
    private function throwValidateBeforeError(DataProperty $property): void
    {
        throw ValidationException::withMessages([
            $property->name => [__('validation.before', [
                'attribute' => $property->name,
                'date' => $this->maxDate->toDateTimeString(),
            ])],
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function throwValidateAfterError(DataProperty $property): void
    {
        throw ValidationException::withMessages([
            $property->name => [__('validation.after', [
                'attribute' => $property->name,
                'date' => $this->minDate->toDateTimeString(),
            ])],
        ]);
    }
}

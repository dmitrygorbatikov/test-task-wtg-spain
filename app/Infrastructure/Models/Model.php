<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Model as BaseModelAlias};
use Illuminate\Support\Facades\{Event, Schema};
use App\Infrastructure\Builders\Builder;
use App\Infrastructure\Traits\HasQuietlyReport;

/**
 * @method static static getModel()
 * @method static static create($attributes = [])
 * @method static static updateOrCreate($updateAttributes = [], $createAttributes = [])
 * @method Builder registerGlobalScopes(EloquentBuilder $builder)
 */
abstract class Model extends BaseModelAlias
{
    use HasQuietlyReport;

    protected bool $relationsChanged = false;

    public function hasRelationsChanged(): bool
    {
        return $this->relationsChanged;
    }

    public function markRelationsChanged(bool $changed = true): void
    {
        $this->relationsChanged = $changed;
    }

    /**
     * @deprecated Check Illuminate\Database\QueryException instead
     */
    public static function hasColumn(string $column): bool
    {
        return Schema::hasColumn(static::getQuery()->from, $column);
    }

    public function disableTimestamps(): void
    {
        $this->timestamps = false;
    }

    public function enableTimestamps(): void
    {
        $this->timestamps = true;
    }

    public function withoutTouchTimestamp(callable $callback): void
    {
        $this->disableTimestamps();
        $callback();
        $this->enableTimestamps();
    }

    public function saveWithoutTouchTimestamp(): void
    {
        $this->withoutTouchTimestamp(function (): void {
            $this->save();
        });
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->getAttribute('created_at');
    }

    public function triggerCreatedEvent(): void
    {
        Event::dispatch('eloquent.created: ' . $this::class, $this);
    }

    public function triggerSavedEvent(): void
    {
        Event::dispatch('eloquent.saved: ' . $this::class, $this);
    }

    public function triggerDeletedEvent(): void
    {
        Event::dispatch('eloquent.deleted: ' . $this::class, $this);
    }

    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    /**
     * @return Builder<static>
     */
    public function newQuery(): Builder
    {
        return $this->registerGlobalScopes($this->newQueryWithoutScopes());
    }

    /**
     * @return Builder<static>
     */
    public static function query(): Builder
    {
        /** @phpstan-ignore-next-line */
        return (new static())->newQuery();
    }
}

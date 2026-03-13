<?php

declare(strict_types=1);

namespace App\Infrastructure\Builders;

use Illuminate\Database\Eloquent\{Builder, Collection, Model};
use Closure;
use Illuminate\Support\LazyCollection;

/**
 * Wrapper for Model template resolving. Delete class after migration to Laravel 11+
 *
 * @template TModel of Model
 */
class EloquentBuilderAdapter extends Builder
{
    /**
     * @phpstan-return  TModel
     */
    public function getModel(): Model
    {
        return parent::getModel();
    }

    /**
     * @return TModel
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return parent::updateOrCreate($attributes, $values);
    }

    /**
     * @return TModel
     */
    public function firstOrCreate(array $attributes = [], array|Closure $values = []): Model
    {
        return parent::firstOrCreate($attributes, $values);
    }

    /**
     * @return TModel
     */
    public function create(array $attributes = []): Model
    {
        return parent::create($attributes);
    }

    /**
     * @param mixed $columns
     *
     * @return Collection<int, TModel>
     */
    public function get($columns = ['*']): Collection
    {
        return parent::get($columns);
    }

    /**
     * @param mixed $column
     * @param null|mixed $operator
     * @param null|mixed $value
     * @param mixed $boolean
     *
     * @return null|TModel
     */
    public function firstWhere($column, $operator = null, $value = null, $boolean = 'and'): ?Model
    {
        return parent::firstWhere($column, $operator, $value, $boolean);
    }

    /**
     * @param mixed $columns
     *
     * @return null|TModel
     */
    public function first($columns = ['*']): ?Model
    {
        return parent::first($columns);
    }

    /**
     * @param mixed $id
     * @param mixed $columns
     *
     * @return null|TModel
     */
    public function find($id, $columns = ['*']): ?Model
    {
        return parent::find($id, $columns);
    }

    /**
     * @param mixed $chunkSize
     *
     * @return LazyCollection<int, TModel>
     */
    public function lazy($chunkSize = 1000)
    {
        return parent::lazy($chunkSize);
    }

    /**
     * @param mixed $chunkSize
     * @param null|mixed $column
     * @param null|mixed $alias
     *
     * @return LazyCollection<int, TModel>
     */
    public function lazyById($chunkSize = 1000, $column = null, $alias = null): LazyCollection
    {
        return parent::lazyById($chunkSize, $column, $alias);
    }

    /**
     * @param callable<TModel, int> $callback
     * @param mixed $count
     */
    public function each(callable $callback, $count = 1000): bool
    {
        return parent::each($callback, $count);
    }

    /**
     * @param callable<Collection<int, TModel>, int> $callback
     * @param mixed $count
     * @param null|mixed $column
     * @param null|mixed $alias
     */
    public function chunkById($count, callable $callback, $column = null, $alias = null): bool
    {
        return parent::chunkById($count, $callback, $column, $alias);
    }

    /**
     * @param callable<TModel, int|string> $callback
     * @param mixed $count
     */
    public function chunk($count, callable $callback): bool
    {
        return parent::chunk($count, $callback);
    }
}

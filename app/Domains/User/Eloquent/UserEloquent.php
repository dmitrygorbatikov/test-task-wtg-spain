<?php

declare(strict_types=1);

namespace App\Domains\User\Eloquent;

use App\Domains\User\Dto\FilterParametersData;
use App\Domains\User\Dto\UserData;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\UserFindException;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Database\Query\{Builder as QueryBuilder, JoinClause};
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\{Arr, Collection as SupportCollection, Facades\DB, Str};
use App\Infrastructure\Dto\SortingParametersData;
use App\Infrastructure\Traits\Eloquent\PrepareQueryTrait;
use RuntimeException;

class UserEloquent
{
    use PrepareQueryTrait;

    public function __construct(
        private readonly User $model,
    ) {
    }

    /**
     * @throws UserFindException
     *
     * @property UserStatusEnum|UserStatusEnum[] $statuses
     */
    public function getBy(string $column, mixed $value, array $select = ['*'], ?UserStatusEnum ...$statuses): User
    {
        try {
            return $this->model::when($statuses, static function (Builder $query) use ($statuses): void {
                $query->whereIn('status', $statuses);
            })
                ->where($column, '=', $value)
                ->select($select)
                ->first() ?? throw new UserFindException($column);
        } catch (QueryException) {
            throw new RuntimeException("Column not found in {$this->model->getTable()} schema. Column - {$column}");
        }
    }

    public function getAvailableUsers(int $page, int $perPage, User $authUser, ?FilterParametersData $filter): LengthAwarePaginator
    {
        $table = $this->model->getTable();
        $authId = $authUser->getKey();

        return $this->model::query()
            ->when($filter?->search, function (Builder $query, string $searchTerm) {
                $searchTerm = trim($searchTerm);

                if (strlen($searchTerm) < 2) {
                    return;
                }

                $searchTerm = mb_strtolower($searchTerm);

                $query->whereRaw("LOWER(name) LIKE ?", ["%{$searchTerm}%"]);
            })

            ->leftJoin('chats', function (JoinClause $join) use ($table, $authId) {
                $join->where(function ($q) use ($table, $authId) {
                    $q->whereColumn("chats.first_id", "{$table}.id")
                        ->where('chats.second_id', $authId);
                })
                    ->orWhere(function ($q) use ($table, $authId) {
                        $q->whereColumn("chats.second_id", "{$table}.id")
                            ->where('chats.first_id', $authId);
                    });
            })

            ->where("{$table}.status", UserStatusEnum::Active)
            ->where("{$table}.id", '!=', $authId)

            ->select([
                "{$table}.*",
                "chats.id as chat_id",
            ])

            ->orderBy("{$table}.created_at", 'desc')

            ->paginate(perPage: $perPage, page: $page);
    }

    public function tryGetBy(
        string $column,
        mixed $value,
        array $select = ['*'],
        ?UserStatusEnum ...$statuses
    ): ?User {
        try {
            return $this->model->newQuery()->when($statuses, static function (Builder $query) use ($statuses): void {
                $query->whereIn('status', $statuses);
            })
                ->where($column, '=', $value)
                ->select($select)
                ->first();
        } catch (QueryException) {
            throw new RuntimeException("Column not found in {$this->model->getTable()} schema. Column - {$column}");
        }
    }

    public function existsBy(string $column, mixed $value, string $operator = '=', ?UserStatusEnum ...$statuses): bool
    {
        try {
            return $this->model::when($statuses, static function (Builder $query) use ($statuses): void {
                $query->whereIn('status', $statuses);
            })->where($column, $operator, $value)->exists();
        } catch (QueryException) {
            throw new RuntimeException('Column not found in User schema. Column - ' . $column);
        }
    }

    /**
     * @throws UserFindException
     */
    public function getByEmail(string $email, array $select = ['*'], UserStatusEnum ...$statuses): User
    {
        return $this->getBy('email', $email, $select, ...$statuses);
    }

    public function generateBasicUserSlug(): string
    {
        do {
            $slug = $this->generateRandomSlug(length: 9, prefix: 'id');
        } while ($this->existsBySlug($slug));

        return $slug;
    }


    private function generateRandomSlug(int $length, string $prefix = ''): string
    {
        $slug = $prefix;

        for ($i = 0; $i < $length; ++$i) {
            $slug .= random_int(0, 9);
        }

        return $slug;
    }

    public function markUserAsVerified(User $user): void
    {
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->status = UserStatusEnum::Active;

        $this->save($user);
    }

    public function existsBySlug(string $slug): bool
    {
        return $this->model->newQuery()->where('slug', '=', $slug)->exists();
    }

    /**
     * @property int[] $ids
     *
     * @return Collection<int, User>
     */
    public function getByIds(array $ids, array $select = ['*'], UserStatusEnum ...$statuses): Collection
    {
        if (empty($ids)) {
            return Collection::make();
        }

        return $this->model::query()
            ->select($select)
            ->whereIn('id', $ids)
            ->when($statuses, function (Builder $b) use ($statuses): void {
                $b->whereIn('status', $statuses);
            })
            ->get();
    }

    /**
     * @return Collection<int, User>
     */
    public function getByEmails(array $emails, array $select = ['*'], UserStatusEnum ...$statuses): Collection
    {
        if (empty($emails)) {
            return Collection::make();
        }

        return $this->model::query()
            ->select($select)
            ->whereIn('email', $emails)
            ->when(!empty($statuses), function (Builder $b) use ($statuses): void {
                $b->whereIn('status', $statuses);
            })
            ->get();
    }

    public function tryGetByEmail(string $email, array $select = ['*'], UserStatusEnum ...$statuses): ?User
    {
        return $this->model::query()
            ->select($select)
            ->when($statuses, static function (Builder $query) use ($statuses): void {
                $query->whereIn('status', $statuses);
            })
            ->firstWhere('email', $email);
    }

    public function tryGetByPhone(string $phone): ?User
    {
        return $this->model::query()
            ->join('phones', function (JoinClause $join) use ($phone): void {
                $join->on('phones.id', '=', 'users.contact_phone_id')
                    ->where('phones.phone', '=', $phone);
            })
            ->first();
    }

    public function create(UserData $userData): User
    {
        return $this->model::create($userData->toArray());
    }


    public function update(User $user, UserData $userData): User
    {
        $user->update($userData->toArray());

        return $user->refresh();
    }

    public function save(User $user): User
    {
        $user->save();

        return $user;
    }

    public function setStatus(User $user, UserStatusEnum $status): bool
    {
        $user->status = $status;

        return $user->save();
    }
}

<?php

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('online', function (User $user) {
    return [
        'id'   => $user->id,
        'name' => $user->name,
    ];
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

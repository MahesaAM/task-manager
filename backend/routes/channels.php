<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('tasks', function ($user) {
    return $user !== null; // Allow all authenticated users via api guard
});

Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    return $user !== null; // Allow all authenticated users via api guard
});

Broadcast::channel('test-channel', function ($user) {
    return $user !== null; // Allow all authenticated users via api guard
});

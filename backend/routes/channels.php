<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('tasks', function ($user) {
    return true; // Allow all authenticated users
});

Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    return true; // Allow all authenticated users for now
});

Broadcast::channel('test-channel', function ($user) {
    return true; // Allow all authenticated users for testing
});

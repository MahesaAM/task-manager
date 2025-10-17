<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::get('/', function () {
    return view('welcome');
});

// Broadcasting routes with API auth
Broadcast::routes(['middleware' => ['auth:api']]);

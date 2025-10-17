<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAttachmentController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\BulkTaskController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\TestSocketController;

Route::get('/test-broadcast', [TestSocketController::class, 'testBroadcast']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:jwt');

// Authentication routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:jwt');
Route::get('user', [AuthController::class, 'me'])->middleware('auth:jwt');

// Broadcasting Authentication (needs to be outside auth middleware for JWT)
Broadcast::routes(['middleware' => ['auth:jwt']]);

// Protected routes
Route::middleware('auth:jwt')->group(function () {
    // Task routes
    Route::apiResource('tasks', TaskController::class);

    // Task attachment routes
    Route::post('tasks/{task}/attachments', [TaskAttachmentController::class, 'store']);
    Route::get('attachments/{attachment}/download', [TaskAttachmentController::class, 'download']);
    Route::delete('attachments/{attachment}', [TaskAttachmentController::class, 'destroy']);

    // Task comment routes
    Route::apiResource('tasks.comments', TaskCommentController::class)->shallow();

    // Bulk operations
    Route::post('tasks/bulk-update-status', [BulkTaskController::class, 'bulkUpdateStatus']);

    // Reports
    Route::post('reports/generate', [ReportController::class, 'generateReport']);
});

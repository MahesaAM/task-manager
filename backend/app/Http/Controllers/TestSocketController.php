<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestSocketController extends Controller
{
    public function testBroadcast()
    {
        $data = [
            'message' => 'Test broadcast message',
            'timestamp' => now()->toDateTimeString(),
            'random' => rand(1000, 9999)
        ];

        Log::info('Broadcasting test message', $data);

        // Broadcast to public channel
        broadcast(new \App\Events\TestEvent($data))->toOthers();

        // Also broadcast to the task.4 channel for testing
        broadcast(new \App\Events\TaskCommentCreated(\App\Models\TaskComment::first()))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Test broadcast sent',
            'data' => $data
        ]);
    }
}
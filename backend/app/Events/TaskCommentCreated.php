<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\TaskComment;

class TaskCommentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(TaskComment $comment)
    {
        $this->comment = $comment;
        Log::info('TaskCommentCreated event created', [
            'comment_id' => $comment->id,
            'task_id' => $comment->task_id,
            'user_id' => $comment->user_id,
            'channel' => 'task.' . $comment->task_id
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = 'task.' . $this->comment->task_id;
        Log::info('Broadcasting on channel', ['channel' => $channel]);
        return [
            new PrivateChannel($channel),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $data = [
            'comment' => $this->comment->load('user'),
        ];
        Log::info('Broadcasting data', $data);
        return $data;
    }
}

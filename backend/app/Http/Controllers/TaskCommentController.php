<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Events\TaskCommentUpdated;
use App\Events\TaskCommentDeleted;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskCommentController extends Controller
{
    /**
     * Get all comments for a specific task
     */
    public function index(Task $task): JsonResponse
    {
        $comments = $task->comments()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Store a new comment for a task
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        $comment->load('user:id,name,email');

        // Broadcast the comment creation event
        broadcast(new CommentCreated($comment));

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * Update a specific comment
     */
    public function update(Request $request, TaskComment $comment): JsonResponse
    {
        // Check if the comment belongs to the user
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this comment'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'comment' => $request->comment,
        ]);

        $comment->load('user:id,name,email');

        // Broadcast the comment update event
        broadcast(new TaskCommentUpdated($comment));

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    /**
     * Delete a specific comment
     */
    public function destroy(TaskComment $comment): JsonResponse
    {
        // Check if the comment belongs to the user
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this comment'
            ], 403);
        }

        $taskId = $comment->task_id;
        $commentId = $comment->id;

        $comment->delete();

        // Broadcast the comment deletion event
        broadcast(new TaskCommentDeleted($commentId, $taskId));

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}

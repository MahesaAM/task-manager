<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with(['assignedUser', 'creator']);

        // Filter by created_by for my-tasks route
        if ($request->has('created_by') && $request->created_by) {
            $query->where('created_by', $request->created_by);
        }

        // Filter by assigned_user_id for assigned-tasks route
        if ($request->has('assigned_user_id') && $request->assigned_user_id) {
            $query->where('assigned_user_id', $request->assigned_user_id);
        }

        // Filtering
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $request->get('per_page', 10);
        $perPage = min(max($perPage, 10), 100); // Ensure per_page is between 10 and 100
        $tasks = $query->paginate($perPage);

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:todo,in-progress,done',
            'priority' => 'in:low,medium,high,urgent',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'medium',
            'assigned_user_id' => $request->assigned_user_id,
            'created_by' => Auth::id(),
            'due_date' => $request->due_date,
        ]);

        // Fire event for real-time updates
        event(new \App\Events\TaskCreated($task));

        return response()->json($task->load(['assignedUser', 'creator']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task->load(['assignedUser', 'creator', 'attachments', 'comments.user'])->loadCount('comments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:todo,in-progress,done',
            'priority' => 'in:low,medium,high,urgent',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldAssignedUserId = $task->assigned_user_id;
        $task->update($request->only([
            'title', 'description', 'status', 'priority', 'assigned_user_id', 'due_date'
        ]));

        // Send notification if task was assigned to a new user
        if ($request->has('assigned_user_id') && $request->assigned_user_id != $oldAssignedUserId && $request->assigned_user_id) {
            $assignedUser = \App\Models\User::find($request->assigned_user_id);
            if ($assignedUser) {
                \App\Jobs\SendTaskAssignmentNotification::dispatch($task, $assignedUser);
            }
        }

        // Fire event for real-time updates
        event(new \App\Events\TaskUpdated($task));

        return response()->json($task->load(['assignedUser', 'creator']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $taskId = $task->id;
        $task->delete();

        // Fire event for real-time updates
        event(new \App\Events\TaskDeleted($taskId));

        return response()->json(['message' => 'Task deleted successfully']);
    }
}

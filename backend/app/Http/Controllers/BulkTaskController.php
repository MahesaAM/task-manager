<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\BulkUpdateTaskStatus;

class BulkTaskController extends Controller
{
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'integer|exists:tasks,id',
            'status' => 'required|in:todo,in-progress,done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Dispatch the bulk update job
        BulkUpdateTaskStatus::dispatch($request->task_ids, $request->status);

        return response()->json([
            'message' => 'Bulk update job has been queued successfully',
            'task_count' => count($request->task_ids),
            'new_status' => $request->status
        ]);
    }
}

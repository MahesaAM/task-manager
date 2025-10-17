<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Jobs\GenerateTaskReport;

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,pdf',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filters = array_filter([
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        // Dispatch the report generation job
        GenerateTaskReport::dispatch(Auth::id(), $filters);

        return response()->json([
            'message' => 'Report generation has been queued. You will be notified when it\'s ready.',
            'format' => $request->format,
            'filters' => $filters
        ]);
    }
}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PDF; // Assuming you have a PDF package like barryvdh/laravel-dompdf

class GenerateTaskReport implements ShouldQueue
{
    use Queueable;

    protected $userId;
    protected $filters;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Generating task report for user: ' . $this->userId);

        $query = Task::where('created_by', $this->userId);

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['priority'])) {
            $query->where('priority', $this->filters['priority']);
        }

        $tasks = $query->with('user', 'comments')->get();

        // Generate CSV report
        $csvContent = "ID,Title,Description,Status,Priority,Due Date,Created At\n";

        foreach ($tasks as $task) {
            $csvContent .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $task->id,
                str_replace('"', '""', $task->title),
                str_replace('"', '""', $task->description ?? ''),
                $task->status,
                $task->priority,
                $task->due_date ?? '',
                $task->created_at
            );
        }

        $filename = 'task-report-' . $this->userId . '-' . now()->format('Y-m-d-H-i-s') . '.csv';
        Storage::disk('public')->put('reports/' . $filename, $csvContent);

        Log::info('Task report generated: ' . $filename);

        // In a real application, you might want to notify the user via email
        // or store the report path in a reports table
    }
}

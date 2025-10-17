<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class BulkUpdateTaskStatus implements ShouldQueue
{
    use Queueable;

    protected $taskIds;
    protected $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(array $taskIds, string $newStatus)
    {
        $this->taskIds = $taskIds;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting bulk update of task status to: ' . $this->newStatus);

        $updatedCount = Task::whereIn('id', $this->taskIds)
            ->update([
                'status' => $this->newStatus,
                'updated_at' => now()
            ]);

        Log::info("Bulk updated {$updatedCount} tasks to status: {$this->newStatus}");

        // Optionally, fire events for each updated task
        $tasks = Task::whereIn('id', $this->taskIds)->get();
        foreach ($tasks as $task) {
            event(new \App\Events\TaskUpdated($task));
        }
    }
}

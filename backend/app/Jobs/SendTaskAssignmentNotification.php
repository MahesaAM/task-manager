<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTaskAssignmentNotification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $assignedUser;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task, User $assignedUser)
    {
        $this->task = $task;
        $this->assignedUser = $assignedUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Sending task assignment notification for task: ' . $this->task->title . ' to user: ' . $this->assignedUser->email);

        // Simulate sending email notification
        // In a real application, you would use Mail::to() to send actual emails
        Log::info('Task Assignment Notification:');
        Log::info('Task: ' . $this->task->title);
        Log::info('Assigned to: ' . $this->assignedUser->name . ' (' . $this->assignedUser->email . ')');
        Log::info('Due Date: ' . $this->task->due_date);
        Log::info('Priority: ' . $this->task->priority);

        // Simulate email sending delay
        sleep(1);

        Log::info('Task assignment notification sent successfully');
    }
}

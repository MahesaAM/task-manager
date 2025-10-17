<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Log;

class ProcessTaskAttachment implements ShouldQueue
{
    use Queueable;

    protected $attachment;

    /**
     * Create a new job instance.
     */
    public function __construct(TaskAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing attachment: ' . $this->attachment->file_name);

        // Dispatch thumbnail generation for images
        if (str_starts_with($this->attachment->mime_type, 'image/')) {
            \App\Jobs\GenerateThumbnail::dispatch($this->attachment);
        }

        // Dispatch virus scanning
        \App\Jobs\ScanVirus::dispatch($this->attachment);

        // Update attachment status
        $this->attachment->update(['processed_at' => now()]);

        Log::info('Attachment processing initiated: ' . $this->attachment->file_name);
    }
}

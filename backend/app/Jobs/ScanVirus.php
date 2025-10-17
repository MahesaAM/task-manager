<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Storage;

class ScanVirus implements ShouldQueue
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
        // Simulate virus scanning
        $filePath = Storage::disk('public')->path($this->attachment->file_path);

        // Simple simulation: check file size and content
        $isInfected = false;

        // Check file size (files over 100MB are "suspicious")
        if ($this->attachment->file_size > 104857600) { // 100MB
            $isInfected = true;
        }

        // Check for suspicious file extensions or content
        $suspiciousExtensions = ['exe', 'bat', 'cmd', 'scr', 'pif', 'com'];
        $extension = strtolower(pathinfo($this->attachment->file_name, PATHINFO_EXTENSION));

        if (in_array($extension, $suspiciousExtensions)) {
            $isInfected = true;
        }

        // Check file content for suspicious patterns (simple simulation)
        if (Storage::disk('public')->exists($this->attachment->file_path)) {
            $content = Storage::disk('public')->get($this->attachment->file_path);
            if (strpos($content, 'virus') !== false || strpos($content, 'malware') !== false) {
                $isInfected = true;
            }
        }

        if ($isInfected) {
            // Delete infected file
            Storage::disk('public')->delete($this->attachment->file_path);
            $this->attachment->delete();

            \Log::warning('Infected file detected and removed: ' . $this->attachment->file_name);
        } else {
            \Log::info('File scanned and found clean: ' . $this->attachment->file_name);
        }
    }
}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Storage;

class GenerateThumbnail implements ShouldQueue
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
        // Check if file is an image
        if (!str_starts_with($this->attachment->mime_type, 'image/')) {
            return;
        }

        $originalPath = $this->attachment->file_path;
        $thumbnailPath = str_replace('.', '_thumb.', $originalPath);

        try {
            // Generate thumbnail using Intervention Image
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read(Storage::disk('public')->path($originalPath));
            $image->cover(200, 200);

            // Save thumbnail
            $image->save(Storage::disk('public')->path($thumbnailPath));

            // Update attachment with thumbnail path (you might need to add a thumbnail_path column)
            // $this->attachment->update(['thumbnail_path' => $thumbnailPath]);

        } catch (\Exception $e) {
            \Log::error('Failed to generate thumbnail: ' . $e->getMessage());
        }
    }
}

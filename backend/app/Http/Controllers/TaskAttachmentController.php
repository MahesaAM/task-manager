<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskAttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:1048576|mimes:jpeg,jpg,png,gif,pdf,doc,docx,txt,html,zip,rar,mp4,avi,mov,app,exe',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['errors' => ['file' => ['The file failed to upload.']]], 422);
        }
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Generate unique filename
        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('task-attachments', $filename, 'public');

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'file_name' => $originalName,
            'file_path' => $path,
            'file_size' => $size,
            'mime_type' => $mimeType,
            'uploaded_at' => now(),
        ]);

        // Dispatch job to process the attachment
        \App\Jobs\ProcessTaskAttachment::dispatch($attachment);

        return response()->json($attachment, 201);
    }

    public function download(TaskAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function destroy(TaskAttachment $attachment)
    {
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully']);
    }
}

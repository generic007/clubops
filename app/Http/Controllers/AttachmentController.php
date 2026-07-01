<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:25600', // 25MB max
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
            'notes' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments/' . date('Y/m'), 'local');

        $attachment = Attachment::create([
            'attachable_type' => $validated['attachable_type'],
            'attachable_id' => $validated['attachable_id'],
            'uploaded_by' => $request->user()->id,
            'filename' => $file->hashName(),
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'disk' => 'local',
            'path' => $path,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function download(Attachment $attachment)
    {
        if (!Storage::disk($attachment->disk)->exists($attachment->path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_filename
        );
    }
}

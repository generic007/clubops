<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,txt,csv|max:25600',
            'attachable_type' => 'required|string|max:255',
            'attachable_id' => 'required|integer',
            'notes' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'private');

        $attachment = Attachment::create([
            'attachable_type' => $request->attachable_type,
            'attachable_id' => $request->attachable_id,
            'uploaded_by' => $request->user()->id,
            'filename' => $file->hashName(),
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'disk' => 'private',
            'path' => $path,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'File uploaded.');
    }

    public function download(Attachment $attachment)
    {
        if (!Storage::disk('private')->exists($attachment->path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download(
            $attachment->path,
            $attachment->original_filename
        );
    }
}

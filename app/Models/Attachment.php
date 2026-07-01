<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'attachable_type', 'attachable_id', 'uploaded_by',
        'filename', 'original_filename', 'mime_type', 'size_bytes',
        'disk', 'path', 'notes',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'uploaded_by');
    }
}

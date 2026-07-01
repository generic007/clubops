<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    protected $table = 'imports';
    protected $fillable = [
        'type', 'filename', 'status', 'total_rows', 'accepted_rows',
        'skipped_rows', 'flagged_rows', 'error_log', 'created_by',
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(ImportRow::class, 'import_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }
}

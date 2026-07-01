<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportRow extends Model
{
    protected $fillable = [
        'import_id', 'row_number', 'raw_data', 'mapped_data',
        'status', 'notes',
    ];

    protected $casts = [
        'raw_data' => 'json',
        'mapped_data' => 'json',
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}

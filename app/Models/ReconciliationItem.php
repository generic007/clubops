<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ReconciliationItem extends Model
{
    protected $fillable = [
        'reconciliation_id', 'entry_id', 'amount', 'type', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reconciliation(): BelongsTo
    {
        return $this->belongsTo(Reconciliation::class);
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(LedgerEntry::class, 'entry_id');
    }
}

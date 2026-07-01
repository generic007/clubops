<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reconciliation extends Model
{
    protected $fillable = [
        'reconciliation_date', 'status', 'platform_total', 'ledger_total',
        'variance', 'created_by', 'locked_by', 'locked_at', 'notes',
    ];

    protected $casts = [
        'reconciliation_date' => 'date',
        'platform_total' => 'decimal:2',
        'ledger_total' => 'decimal:2',
        'variance' => 'decimal:2',
        'locked_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ReconciliationItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }

    public function locker(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'locked_by');
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }

    public function hasVariance(): bool
    {
        return abs($this->variance) > 0.01;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TransactionType;

class LedgerEntry extends Model
{
    protected $fillable = [
        'entry_number', 'type', 'description', 'created_by',
        'source_type', 'source_id', 'reference', 'entry_date',
        'reversed_entry_id', 'locked',
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'locked' => 'boolean',
        'entry_date' => 'date',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(LedgerLine::class, 'entry_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }

    public function reversedEntry(): BelongsTo
    {
        return $this->belongsTo(LedgerEntry::class, 'reversed_entry_id');
    }

    public function reversalFor(): HasMany
    {
        return $this->hasMany(LedgerEntry::class, 'reversed_entry_id');
    }

    public function isBalanced(): bool
    {
        $totalDebit = $this->lines()->sum('debit');
        $totalCredit = $this->lines()->sum('credit');
        return abs($totalDebit - $totalCredit) < 0.01;
    }

    public function lock(): void
    {
        $this->update(['locked' => true]);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('entry_date', $date);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('locked', false);
    }
}

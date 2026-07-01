<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerLine extends Model
{
    protected $fillable = [
        'entry_id', 'account_id', 'player_id', 'debit', 'credit',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(LedgerEntry::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(LedgerAccount::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

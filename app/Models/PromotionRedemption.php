<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionRedemption extends Model
{
    protected $fillable = [
        'promotion_id', 'player_id', 'ledger_entry_id',
        'amount', 'status', 'notes', 'claimed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'claimed_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function ledgerEntry(): BelongsTo
    {
        return $this->belongsTo(LedgerEntry::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exclusion extends Model
{
    protected $fillable = [
        'player_id', 'type', 'starts_at', 'ends_at', 'reason', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->starts_at <= now()
            && ($this->ends_at === null || $this->ends_at >= now());
    }
}

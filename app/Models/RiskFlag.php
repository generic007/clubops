<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskFlag extends Model
{
    protected $fillable = [
        'player_id', 'raised_by', 'resolved_by', 'type',
        'description', 'severity', 'status', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'raised_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'resolved_by');
    }
}

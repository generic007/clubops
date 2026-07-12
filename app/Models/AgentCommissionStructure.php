<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentCommissionStructure extends Model
{
    protected $fillable = [
        'club_id', 'agent_id', 'type', 'rate', 'tiers', 'active',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'tiers' => 'json',
        'active' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}

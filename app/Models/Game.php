<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'name', 'type', 'stakes', 'platform', 'status',
        'scheduled_at', 'started_at', 'ended_at', 'notes', 'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }
}

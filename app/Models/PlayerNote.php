<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerNote extends Model
{
    protected $fillable = ['player_id', 'agent_id', 'note', 'category'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}

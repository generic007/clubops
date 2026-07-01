<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerPlatformAccount extends Model
{
    protected $fillable = ['player_id', 'platform', 'username', 'user_id', 'verified'];

    protected $casts = ['verified' => 'boolean'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

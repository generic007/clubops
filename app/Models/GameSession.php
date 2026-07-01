<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    protected $fillable = [
        'game_id', 'player_id', 'buy_in', 'cash_out',
        'profit_loss', 'duration_minutes', 'notes',
    ];

    protected $casts = [
        'buy_in' => 'decimal:2',
        'cash_out' => 'decimal:2',
        'profit_loss' => 'decimal:2',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

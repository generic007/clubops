<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'player_tag');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceProfile extends Model
{
    protected $fillable = [
        'player_id', 'date_of_birth', 'location',
        'id_verification_status', 'id_verified_at', 'compliance_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'id_verified_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

<?php

namespace App\Models;

use App\Enums\AgentRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClubInvitation extends Model
{
    protected $fillable = [
        'club_id', 'email', 'role', 'token', 'invited_by',
        'message', 'expires_at', 'accepted_at',
    ];

    protected $casts = [
        'role' => AgentRole::class,
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ClubInvitation $invitation) {
            $invitation->token = $invitation->token ?? Str::random(40);
            $invitation->expires_at = $invitation->expires_at ?? now()->addDays(7);
        });
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function scopePending($query)
    {
        return $query->whereNull('accepted_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->whereNull('accepted_at')
            ->where('expires_at', '<=', now());
    }
}

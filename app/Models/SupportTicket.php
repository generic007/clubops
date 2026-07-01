<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number', 'player_id', 'assigned_to', 'subject',
        'description', 'type', 'priority', 'status', 'resolved_at',
    ];

    protected $casts = [
        'type' => TicketType::class,
        'status' => TicketStatus::class,
        'resolved_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [TicketStatus::Open, TicketStatus::InProgress]);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [TicketStatus::Open, TicketStatus::InProgress]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TicketComment extends Model
{
    protected $fillable = ['ticket_id', 'author_type', 'author_id', 'body'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}

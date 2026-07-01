<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'author_type' => get_class($request->user()),
            'author_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        if ($ticket->status->value === 'open') {
            $ticket->update(['status' => \App\Enums\TicketStatus::InProgress]);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Comment added.');
    }
}

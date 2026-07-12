<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Player;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Http\Requests\StoreTicketRequest;
use App\Traits\ExportsCsv;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    use ExportsCsv;
    public function index(Request $request)
    {
        $query = SupportTicket::with(['player', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->user()->isSupport() || $request->user()->isAgent()) {
            $query->where(function ($q) use ($request) {
                $q->where('assigned_to', $request->user()->id)
                  ->orWhereNull('assigned_to');
            });
        }

        $tickets = $query->latest()->paginate(25);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $players = Player::active()->orderBy('name')->get();
        return view('tickets.create', compact('players'));
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = SupportTicket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'player_id' => $request->player_id,
            'assigned_to' => $request->user()->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => TicketStatus::Open,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->ticket_number} created.");
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['player', 'assignedTo', 'comments.author', 'attachments']);
        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'sometimes|string|in:' . implode(',', array_column(TicketStatus::cases(), 'value')),
            'assigned_to' => 'nullable|exists:agents,id',
            'subject' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $data = $validated;

        if (isset($validated['status']) && in_array($validated['status'], ['resolved', 'closed'])) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated.');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->update(['status' => TicketStatus::Closed]);
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket closed.');
    }

    protected function generateTicketNumber(): string
    {
        $prefix = 'TKT-';
        do {
            $number = $prefix . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (SupportTicket::where('ticket_number', $number)->exists());

        return $number;
    }

    public function export(Request $request)
    {
        $query = SupportTicket::with(['player', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $tickets = $query->latest()->get();

        return $this->exportCsv($tickets, [
            'Ticket #', 'Subject', 'Player', 'Type', 'Priority', 'Status', 'Assigned To', 'Created',
        ], function ($ticket) {
            return [
                $ticket->ticket_number,
                $ticket->subject,
                $ticket->player?->name ?? '',
                $ticket->type->value,
                $ticket->priority,
                $ticket->status->value,
                $ticket->assignedTo?->name ?? '',
                $ticket->created_at->format('Y-m-d'),
            ];
        }, 'tickets-' . now()->format('Y-m-d') . '.csv');
    }
}

@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎫 Support Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">+ New Ticket</a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tickets.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach(\App\Enums\TicketStatus::cases() as $s)
                        <option value="{{ $s->value }}" {{ request('status') == $s->value ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All</option>
                    @foreach(\App\Enums\TicketType::cases() as $t)
                        <option value="{{ $t->value }}" {{ request('type') == $t->value ? 'selected' : '' }}>
                            {{ str_replace('_', ' ', ucfirst($t->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Tickets Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Player</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="fw-semibold">{{ $ticket->ticket_number }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-decoration-none">
                                {{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}
                            </a>
                        </td>
                        <td>
                            @if($ticket->player)
                                <a href="{{ route('players.show', $ticket->player) }}">{{ $ticket->player->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $ticket->type->value) }}</span></td>
                        <td>
                            <span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $ticket->status->value }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status->value)) }}
                            </span>
                        </td>
                        <td>{{ $ticket->assignedTo?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No tickets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tickets->hasPages())
    <div class="card-footer bg-white">
        {{ $tickets->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

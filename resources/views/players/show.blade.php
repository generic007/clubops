@extends('layouts.app')

@section('title', 'Player Profile')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $player->preferred_name ?? $player->name }}</h1>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge-status badge-{{ $player->status->value }}">{{ ucfirst($player->status->value) }}</span>
            <small class="text-muted">Player #{{ $player->id }}</small>
            @if($player->isExcluded())
                <span class="badge bg-danger">Excluded</span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('players.edit', $player) }}" class="btn btn-outline-primary">✏️ Edit</a>
        <form method="POST" action="{{ route('players.contacted', $player) }}" class="d-inline">
            @csrf
            <button class="btn btn-outline-success">📲 Contacted</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Info Card -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white"><strong>📋 Details</strong></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted">Email</td><td>{{ $player->email ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ $player->phone ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Referral</td><td>{{ $player->referral_source ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Agent</td><td>{{ $player->agent?->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Admin</td><td>{{ $player->assignedAdmin?->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Risk Status</td><td>{{ $player->risk_status?->value ?? 'low' }}</td></tr>
                    <tr><td class="text-muted">Compliance</td><td>{!! $player->compliance_complete ? '✅ Complete' : '⏳ Pending' !!}</td></tr>
                    <tr><td class="text-muted">Last Played</td><td>{{ $player->last_played_at?->diffForHumans() ?? 'Never' }}</td></tr>
                    <tr><td class="text-muted">Last Contact</td><td>{{ $player->last_contacted_at?->diffForHumans() ?? 'Never' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Balance & Platform Accounts -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><strong>💰 Balance</strong></div>
            <div class="card-body text-center">
                <div style="font-size:2.5rem;font-weight:800;color:{{ $balance >= 0 ? '#16a34a' : '#dc2626' }};">
                    ${{ number_format(abs($balance), 2) }}
                </div>
                <div class="text-muted">{{ $balance >= 0 ? 'Positive' : 'Negative' }}</div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong>🎮 Platform Accounts</strong>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#platformModal">+ Add</button>
            </div>
            <div class="card-body">
                @forelse($player->platformAccounts as $acct)
                    <div class="d-flex justify-content-between py-1">
                        <span><strong>{{ $acct->platform }}</strong>: {{ $acct->username }}</span>
                        <span>{!! $acct->verified ? '✅' : '⏳' !!}</span>
                    </div>
                @empty
                    <div class="text-muted">No platform accounts linked.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tags -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🏷️ Tags</strong></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($player->tags as $tag)
                        <span class="badge bg-secondary">{{ $tag->name }}</span>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('players.tags.store', $player) }}" class="input-group input-group-sm">
                    @csrf
                    <input type="text" name="tag" class="form-control" placeholder="New tag">
                    <button class="btn btn-outline-primary">Add</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notes Timeline -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📝 Notes</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('players.notes.store', $player) }}" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="note" class="form-control" placeholder="Add a note..." required>
                        <button class="btn btn-primary">+ Add</button>
                    </div>
                </form>
                <div style="max-height:300px;overflow-y:auto;">
                    @forelse($player->notes->sortByDesc('created_at') as $note)
                        <div class="border-bottom pb-2 mb-2">
                            <small class="text-muted">{{ $note->agent?->name }} · {{ $note->created_at->diffForHumans() }}</small>
                            <div>{{ $note->note }}</div>
                        </div>
                    @empty
                        <div class="text-muted">No notes yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Flags -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>⚠️ Risk Flags</strong></div>
            <div class="card-body">
                @forelse($player->riskFlags as $flag)
                    <div class="border-bottom pb-2 mb-2">
                        <span class="badge bg-{{ $flag->severity === 'high' ? 'danger' : ($flag->severity === 'critical' ? 'dark' : 'warning') }}">{{ $flag->type }}</span>
                        <span class="text-muted small">{{ $flag->created_at->diffForHumans() }}</span>
                        <div class="mt-1">{{ $flag->description }}</div>
                    </div>
                @empty
                    <div class="text-muted">No risk flags.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Promo Redemptions -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🎁 Promo History</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Promo</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($player->promoRedemptions as $r)
                        <tr>
                            <td>{{ $r->promotion?->name }}</td>
                            <td>${{ number_format($r->amount, 2) }}</td>
                            <td>{{ $r->status }}</td>
                            <td>{{ $r->claimed_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted text-center">No redemptions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tickets -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🎫 Support Tickets</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th>Subject</th><th>Type</th><th>Priority</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($player->tickets as $ticket)
                        <tr>
                            <td><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->ticket_number }}</a></td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ str_replace('_', ' ', $ticket->type->value) }}</td>
                            <td><span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">{{ $ticket->priority }}</span></td>
                            <td>{{ $ticket->status->value }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-muted text-center">No tickets.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

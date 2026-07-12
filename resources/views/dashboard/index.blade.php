@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">{{ $club->name }}</h1>
            <p class="text-muted mb-0">
                {{ now()->format('l, F j, Y') }} &middot;
                {{ $activePlayers }} active players
                @if($newThisWeek > 0)
                    &middot; <span class="text-success">+{{ $newThisWeek }} new this week</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#buyInModal">
                💰 Buy-In
            </button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cashOutModal">
                💸 Cash-Out
            </button>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon">👥</div>
                <div>
                    <div class="kpi-value">{{ $totalPlayers }}</div>
                    <div class="kpi-label">Total Players</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(16,185,129,.1);">🟢</div>
                <div>
                    <div class="kpi-value">{{ $activePlayers }}</div>
                    <div class="kpi-label">Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(245,158,11,.1);">📊</div>
                <div>
                    <div class="kpi-value">${{ number_format($todayVolume, 0) }}</div>
                    <div class="kpi-label">Today's Volume</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(239,68,68,.1);">🎫</div>
                <div>
                    <div class="kpi-value">{{ $openTickets }}</div>
                    <div class="kpi-label">Open Tickets</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity + Quick Actions -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <strong>📒 Recent Activity</strong>
                <a href="{{ route('ledger.entries.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentEntries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEntries as $entry)
                                <tr>
                                    <td>
                                        <a href="{{ route('players.show', $entry->player) }}" class="fw-semibold">
                                            {{ $entry->player?->name ?? '—' }}
                                        </a>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ str_replace('_', ' ', $entry->type->value) }}</span></td>
                                    <td class="amount">
                                        @php
                                            $line = $entry->lines->first();
                                        @endphp
                                        @if($line)
                                            <span class="{{ $line->debit > 0 ? 'text-danger' : ($line->credit > 0 ? 'text-success' : '') }}">
                                                @if($line->debit > 0)-${{ number_format($line->debit, 2) }}@else +${{ number_format($line->credit, 2) }}@endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="white-space:nowrap;">{{ $entry->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-title">No activity yet</div>
                        <div class="empty-text">Record your first buy-in or cash-out to get started.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Stats -->
        <div class="card mb-3">
            <div class="card-header"><strong>⚡ Quick Actions</strong></div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('players.create') }}" class="btn btn-outline-primary">
                        ➕ Add Player
                    </a>
                    <a href="{{ route('ledger.entries.create') }}" class="btn btn-outline-info">
                        📝 Record Entry
                    </a>
                    <a href="{{ route('reconciliations.create') }}" class="btn btn-outline-warning">
                        ✅ Run Reconciliation
                    </a>
                    @if(\App\ClubOpsEdition::isPro())
                    <a href="{{ route('invitations.index') }}" class="btn btn-outline-success">
                        👤 Invite Team Member
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Players -->
        @if($recentPlayers->count() > 0)
        <div class="card">
            <div class="card-header"><strong>🎮 Recent Players</strong></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($recentPlayers as $p)
                    <a href="{{ route('players.show', $p) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $p->name }}</div>
                            <small class="text-muted">Last played {{ $p->last_played_at->diffForHumans() }}</small>
                        </div>
                        <span class="badge bg-{{ $p->status->value === 'active' ? 'success' : 'secondary' }} text-dark">
                            {{ ucfirst($p->status->value) }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Buy-In Modal -->
<div class="modal fade" id="buyInModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('quick.buy-in') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">💰 Record Buy-In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select a player…</option>
                            @foreach($quickPlayers as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} @if($p->email)({{ $p->email }})@endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buy-In Amount ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="e.g. 200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <small class="text-muted">(optional)</small></label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. $1/$2 NLH">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">💰 Record Buy-In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cash-Out Modal -->
<div class="modal fade" id="cashOutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('quick.cash-out') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">💸 Record Cash-Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select a player…</option>
                            @foreach($quickPlayers as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} @if($p->email)({{ $p->email }})@endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cash-Out Amount ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="e.g. 350" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <small class="text-muted">(optional)</small></label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. $1/$2 NLH — cashed for $350">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">💸 Record Cash-Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

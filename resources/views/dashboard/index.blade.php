@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📊 Dashboard</h1>
    <div>
        <span class="badge bg-{{ $dailyCloseStatus === 'locked' ? 'success' : 'warning' }} fs-6">
            Daily Close: {{ ucfirst($dailyCloseStatus) }}
        </span>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $activePlayers }}</div>
                    <div class="kpi-label">Active Players</div>
                </div>
                <div class="kpi-icon">👥</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $newLeads }}</div>
                    <div class="kpi-label">New Leads (week)</div>
                </div>
                <div class="kpi-icon">📥</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $pendingOnboarding }}</div>
                    <div class="kpi-label">Pending Onboarding</div>
                </div>
                <div class="kpi-icon">⏳</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value" style="color: #64748b;">{{ $inactivePlayers }}</div>
                    <div class="kpi-label">Inactive</div>
                </div>
                <div class="kpi-icon">💤</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value" style="color: {{ $highRiskPlayers > 0 ? '#ef4444' : '#10b981' }};">{{ $highRiskPlayers }}</div>
                    <div class="kpi-label">High Risk</div>
                </div>
                <div class="kpi-icon">⚠️</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $unresolvedTickets }}</div>
                    <div class="kpi-label">Open Tickets</div>
                </div>
                <div class="kpi-icon">🎫</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $reconMismatches }}</div>
                    <div class="kpi-label">Recon Mismatches</div>
                </div>
                <div class="kpi-icon">⚠️</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">${{ number_format($openPromoLiability, 0) }}</div>
                    <div class="kpi-label">Promo Liability</div>
                </div>
                <div class="kpi-icon">🎁</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value">{{ $dormantVips }}</div>
                    <div class="kpi-label">Dormant VIPs</div>
                </div>
                <div class="kpi-icon">😴</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="kpi-value kpi-close" style="color: {{ $dailyCloseStatus === 'locked' ? '#16a34a' : '#dc2626' }};">
                        {{ ucfirst($dailyCloseStatus) }}
                    </div>
                    <div class="kpi-label">Day Status</div>
                </div>
                <div class="kpi-icon">{{ $dailyCloseStatus === 'locked' ? '🔒' : '🔓' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Players -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>📋 Recent Players</strong>
                <a href="{{ route('players.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Name</th><th>Status</th><th>Agent</th><th>Created</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentPlayers as $player)
                        <tr>
                            <td><a href="{{ route('players.show', $player) }}">{{ $player->name }}</a></td>
                            <td><span class="badge-status badge-{{ $player->status->value }}">{{ $player->status->value }}</span></td>
                            <td>{{ $player->agent?->name ?? '—' }}</td>
                            <td>{{ $player->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No players yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Ledger Entries -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>💰 Recent Ledger Entries</strong>
                <a href="{{ route('ledger.entries.create') }}" class="btn btn-sm btn-primary">+ New Entry</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>#</th><th>Type</th><th>Amount</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentEntries as $entry)
                        <tr>
                            <td><a href="{{ route('ledger.entries.show', $entry) }}">{{ $entry->entry_number }}</a></td>
                            <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $entry->type) }}</span></td>
                            <td>${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                            <td>{{ $entry->entry_date->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No entries yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Open Tickets -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>🎫 Open Tickets</strong>
                <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary">+ New Ticket</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>#</th><th>Subject</th><th>Priority</th><th>Assigned</th></tr>
                    </thead>
                    <tbody>
                        @forelse($openTickets as $ticket)
                        <tr>
                            <td><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->ticket_number }}</a></td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 30) }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No open tickets</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <strong>⚡ Quick Actions</strong>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('players.create') }}" class="btn btn-outline-primary">👤 Add Player</a>
                    <a href="{{ route('ledger.entries.create') }}" class="btn btn-outline-success">💰 New Entry</a>
                    <a href="{{ route('reconciliations.create') }}" class="btn btn-outline-warning">✅ Reconcile</a>
                    <a href="{{ route('tickets.create') }}" class="btn btn-outline-info">🎫 New Ticket</a>
                    <a href="{{ route('imports.create') }}" class="btn btn-outline-secondary">📥 Import</a>
                    <a href="{{ route('promotions.create') }}" class="btn btn-outline-danger">🎁 New Promo</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

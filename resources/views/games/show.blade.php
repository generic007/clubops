@extends('layouts.app')

@section('title', $game->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $game->name }}</h1>
        <div class="text-muted">
            {{ $game->type }} / {{ $game->stakes }} · {{ $game->platform }}
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('games.edit', $game) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

<!-- Game Info -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">@php
                $statusColors = ['scheduled' => '#3b82f6', 'running' => '#10b981', 'completed' => '#64748b', 'cancelled' => '#ef4444'];
            @endphp
            <span style="color: {{ $statusColors[$game->status] ?? '#64748b' }}">{{ ucfirst($game->status) }}</span></div>
            <div class="kpi-label">Status</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">{{ $playerCount }}</div>
            <div class="kpi-label">Players</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalBuyins, 0) }}</div>
            <div class="kpi-label">Total Buy-ins</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalCashouts, 0) }}</div>
            <div class="kpi-label">Total Cash-outs</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Game Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>📋 Game Details</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Name</td>
                        <td class="fw-semibold">{{ $game->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>{{ $game->type }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Stakes</td>
                        <td>{{ $game->stakes }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Platform</td>
                        <td><span class="badge bg-secondary">{{ $game->platform }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Scheduled</td>
                        <td>{{ $game->scheduled_at->format('l, F j, Y g:i A') }}</td>
                    </tr>
                    @if($game->started_at)
                    <tr>
                        <td class="text-muted">Started</td>
                        <td>{{ $game->started_at->format('M d, g:i A') }}</td>
                    </tr>
                    @endif
                    @if($game->ended_at)
                    <tr>
                        <td class="text-muted">Ended</td>
                        <td>{{ $game->ended_at->format('M d, g:i A') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Created by</td>
                        <td>{{ $game->creator?->name ?? '—' }}</td>
                    </tr>
                </table>
                @if($game->notes)
                <hr>
                <div class="text-muted small">Notes</div>
                <p class="mb-0">{{ $game->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Player Sessions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>👥 Player Sessions</strong>
                @if(in_array($game->status, ['scheduled', 'running']))
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                    + Add Player
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Player</th><th>Buy-in</th><th>Cash-out</th><th>P/L</th></tr>
                    </thead>
                    <tbody>
                        @forelse($game->sessions as $session)
                        <tr>
                            <td>{{ $session->player?->name ?? 'Unknown' }}</td>
                            <td class="amount">${{ number_format($session->buy_in, 2) }}</td>
                            <td class="amount">{{ $session->cash_out ? '$'.number_format($session->cash_out, 2) : '—' }}</td>
                            <td class="amount {{ $session->profit_loss && $session->profit_loss > 0 ? 'text-success' : ($session->profit_loss && $session->profit_loss < 0 ? 'text-danger' : '') }}">
                                {{ $session->profit_loss !== null ? '$'.number_format($session->profit_loss, 2) : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No players added yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Session Modal -->
@if(in_array($game->status, ['scheduled', 'running']))
<div class="modal fade" id="addSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('games.sessions.start', $game) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Player to Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select player...</option>
                            @foreach(\App\Models\Player::active()->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buy-in Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="buy_in" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Player</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

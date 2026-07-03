@extends('layouts.app')

@section('title', 'Games')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Games</h1>
    <a href="{{ route('games.create') }}" class="btn btn-primary">+ New Game</a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Platform</label>
                <select name="platform" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($platforms as $p)
                        <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<!-- Games Table -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Type / Stakes</th>
                    <th>Platform</th>
                    <th>Scheduled</th>
                    <th>Status</th>
                    <th>Players</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($games as $game)
                <tr>
                    <td><a href="{{ route('games.show', $game) }}" class="fw-semibold">{{ $game->name }}</a></td>
                    <td>{{ $game->type }} / {{ $game->stakes }}</td>
                    <td><span class="badge bg-secondary">{{ $game->platform }}</span></td>
                    <td>{{ $game->scheduled_at->format('M d, g:i A') }}</td>
                    <td>
                        @php
                            $statusColors = ['scheduled' => 'info', 'running' => 'success', 'completed' => 'secondary', 'cancelled' => 'danger'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$game->status] ?? 'secondary' }}">
                            {{ ucfirst($game->status) }}
                        </span>
                    </td>
                    <td>{{ $game->sessions_count ?? $game->sessions()->count() }}</td>
                    <td>
                        <a href="{{ route('games.edit', $game) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="empty-icon">🎮</span>
                            <div class="empty-title">No games scheduled</div>
                            <div class="empty-text">Schedule your first game to start tracking sessions and player activity.</div>
                            <a href="{{ route('games.create') }}" class="btn btn-primary empty-action">Schedule Game</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($games->hasPages())
    <div class="card-footer">
        {{ $games->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Players')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">👥 Players</h1>
    <a href="{{ route('players.create') }}" class="btn btn-primary">+ Add Player</a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>{{ ucfirst($s->value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="tag" class="form-select">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->name }}" {{ request('tag') === $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Players Table -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Platform</th>
                    <th>Agent</th>
                    <th>Tags</th>
                    <th>Last Played</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($players as $player)
                <tr>
                    <td>
                        <a href="{{ route('players.show', $player) }}" class="fw-semibold">{{ $player->preferred_name ?? $player->name }}</a>
                        <small class="d-block text-muted">{{ $player->name !== $player->preferred_name ? $player->name : '' }}</small>
                    </td>
                    <td><span class="badge-status badge-{{ $player->status->value }}">{{ ucfirst($player->status->value) }}</span></td>
                    <td>
                        @foreach($player->platformAccounts as $acct)
                            <small class="d-block">{{ $acct->platform }}: {{ $acct->username }}</small>
                        @endforeach
                    </td>
                    <td>{{ $player->agent?->name ?? '—' }}</td>
                    <td>
                        @foreach($player->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $player->last_played_at ? $player->last_played_at->diffForHumans() : '—' }}</td>
                    <td>{{ $player->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No players found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($players->hasPages())
    <div class="card-footer">
        {{ $players->links() }}
    </div>
    @endif
</div>
@endsection

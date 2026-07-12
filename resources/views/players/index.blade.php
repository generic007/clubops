@extends('layouts.app')

@section('title', 'Players')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">👥 Players</h1>
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('players.export') }}" class="btn btn-outline-secondary btn-sm">📥 Export</a>
        <a href="{{ route('players.create') }}" class="btn btn-primary">+ Add Player</a>
    </div>
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

<!-- Quick Tag Modal -->
<div class="modal fade" id="quickTagModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="quickTagForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">🏷️ Add Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag Name</label>
                        <input type="text" name="tag" class="form-control" placeholder="Enter tag name..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openTagForm(playerId) {
    const form = document.getElementById('quickTagForm');
    form.action = '/players/' + playerId + '/tags';
    const modal = new bootstrap.Modal(document.getElementById('quickTagModal'));
    modal.show();
}
</script>

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
                    <th></th>
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
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                            @foreach($player->tags as $tag)
                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @endforeach
                            <button class="btn btn-sm btn-outline-secondary border-0 px-1 py-0" onclick="openTagForm({{ $player->id }})" title="Add tag">➕</button>
                        </div>
                    </td>
                    <td>{{ $player->last_played_at ? $player->last_played_at->diffForHumans() : '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('players.show', $player) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <span class="empty-icon">👥</span>
                            <div class="empty-title">No players yet</div>
                            <div class="empty-text">Add your first player to start tracking sessions, buy-ins, and cash-outs.</div>
                            <a href="{{ route('players.create') }}" class="btn btn-primary empty-action">+ Add Player</a>
                        </div>
                    </td>
                </tr>
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

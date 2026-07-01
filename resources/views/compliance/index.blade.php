@extends('layouts.app')

@section('title', 'Compliance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🔒 Compliance Overview</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('compliance.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Player name or email..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Compliance Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="complete" {{ request('status') === 'complete' ? 'selected' : '' }}>Complete</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="excluded" {{ request('status') === 'excluded' ? 'selected' : '' }}>Excluded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ID Verification</label>
                <select name="id_status" class="form-select">
                    <option value="">All</option>
                    <option value="verified" {{ request('id_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('id_status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    <option value="pending" {{ request('id_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-success">{{ $completeCount }}</div>
            <div class="kpi-label">Compliance Complete</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-warning">{{ $pendingCount }}</div>
            <div class="kpi-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-danger">{{ $excludedCount }}</div>
            <div class="kpi-label">Excluded</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $idVerifiedCount }}</div>
            <div class="kpi-label">ID Verified</div>
        </div>
    </div>
</div>

<!-- Players Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Status</th>
                        <th>Compliance</th>
                        <th>ID Verified</th>
                        <th>Location</th>
                        <th>DOB</th>
                        <th>Excluded</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                    <tr class="{{ $player->isExcluded() ? 'table-danger' : '' }}">
                        <td>
                            <a href="{{ route('compliance.show', $player) }}" class="fw-semibold">{{ $player->name }}</a>
                        </td>
                        <td><span class="badge-status badge-{{ $player->status->value }}">{{ ucfirst($player->status->value) }}</span></td>
                        <td>
                            @if($player->compliance_complete)
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($player->compliance && $player->compliance->id_verification_status === 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($player->compliance && $player->compliance->id_verification_status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-secondary">Unverified</span>
                            @endif
                        </td>
                        <td>{{ $player->compliance?->location ?? '—' }}</td>
                        <td>{{ $player->compliance?->date_of_birth?->format('M d, Y') ?? '—' }}</td>
                        <td>
                            @if($player->isExcluded())
                                <span class="badge bg-danger">⚠ Excluded</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('compliance.show', $player) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No players found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($players->hasPages())
    <div class="card-footer bg-white">
        {{ $players->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

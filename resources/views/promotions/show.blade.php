@extends('layouts.app')

@section('title', $promotion->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🎁 {{ $promotion->name }}
        @if($promotion->isActive())
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-secondary">{{ $promotion->active ? 'Scheduled' : 'Inactive' }}</span>
        @endif
    </h1>
    <div>
        <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">{{ str_replace('_', ' ', ucfirst($promotion->type->value)) }}</div>
            <div class="kpi-label">Type</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($promotion->value, 2) }}</div>
            <div class="kpi-label">Value</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($promotion->cap ?? 0, 2) }}</div>
            <div class="kpi-label">Cap @if(!$promotion->cap)<small class="text-muted">(unlimited)</small>@endif</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($promotion->claimed_liability ?? 0, 2) }}</div>
            <div class="kpi-label">Claimed</div>
        </div>
    </div>
</div>

<!-- Liability Meter -->
@if($promotion->cap > 0)
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <strong>Liability Meter</strong>
        @php $pct = min(100, ($promotion->claimed_liability / $promotion->cap) * 100); @endphp
        <div class="progress mt-2" style="height: 24px;">
            <div class="progress-bar bg-{{ $pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success') }}"
                 role="progressbar" style="width: {{ $pct }}%"
                 aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                ${{ number_format($promotion->claimed_liability, 0) }} / ${{ number_format($promotion->cap, 0) }} ({{ number_format($pct, 1) }}%)
            </div>
        </div>
        <small class="text-muted mt-1 d-block">Remaining: ${{ number_format(max(0, $promotion->cap - $promotion->claimed_liability), 2) }}</small>
    </div>
</div>
@endif

<!-- Promo Details -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white"><strong>📋 Details</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-2">Description</dt>
            <dd class="col-sm-10">{{ $promotion->description ?? '—' }}</dd>

            <dt class="col-sm-2">Period</dt>
            <dd class="col-sm-10">
                {{ $promotion->starts_at->format('M d, Y g:i A') }}
                @if($promotion->ends_at)
                    — {{ $promotion->ends_at->format('M d, Y g:i A') }}
                @else
                    — No end date
                @endif
            </dd>

            <dt class="col-sm-2">Terms</dt>
            <dd class="col-sm-10">{{ $promotion->terms ?? '—' }}</dd>

            <dt class="col-sm-2">Created</dt>
            <dd class="col-sm-10">{{ $promotion->created_at->format('M d, Y g:i A') }}</dd>
        </dl>
    </div>
</div>

<!-- Redemptions Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>📋 Redemptions</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Ledger Entry</th>
                        <th>Claimed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotion->redemptions as $redemption)
                    <tr>
                        <td>
                            <a href="{{ route('players.show', $redemption->player) }}">{{ $redemption->player->name }}</a>
                        </td>
                        <td>${{ number_format($redemption->amount, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $redemption->status }}</span></td>
                        <td>
                            @if($redemption->ledgerEntry)
                                <a href="{{ route('ledger.entries.show', $redemption->ledgerEntry) }}">
                                    {{ $redemption->ledgerEntry->entry_number }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $redemption->claimed_at?->format('M d, Y g:i A') ?? $redemption->created_at->format('M d, Y g:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No redemptions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

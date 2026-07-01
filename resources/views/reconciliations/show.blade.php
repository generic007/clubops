@extends('layouts.app')

@section('title', 'Reconciliation - ' . $reconciliation->reconciliation_date->format('M d, Y'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        ✅ Reconciliation: {{ $reconciliation->reconciliation_date->format('F d, Y') }}
    </h1>
    <div>
        <a href="{{ route('reconciliations.index') }}" class="btn btn-outline-secondary">Back</a>
        @if(!$reconciliation->isLocked() && !$reconciliation->hasVariance())
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#lockModal">
            🔒 Lock & Close Day
        </button>
        @endif
    </div>
</div>

@if($reconciliation->hasVariance())
<div class="alert alert-danger">
    ⚠️ <strong>Variance detected:</strong> ${{ number_format($reconciliation->variance, 2) }}
    difference between platform (${{ number_format($reconciliation->platform_total, 2) }})
    and ledger (${{ number_format($reconciliation->ledger_total, 2) }}).
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">${{ number_format($reconciliation->platform_total, 2) }}</div>
            <div class="kpi-label">Platform Total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">${{ number_format($reconciliation->ledger_total, 2) }}</div>
            <div class="kpi-label">Ledger Total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value {{ $reconciliation->hasVariance() ? 'text-danger' : 'text-success' }}">
                ${{ number_format($reconciliation->variance, 2) }}
            </div>
            <div class="kpi-label">Variance</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">
                @if($reconciliation->isLocked())
                    <span class="text-success">🔒 Locked</span>
                @else
                    <span class="text-warning">🔓 Open</span>
                @endif
            </div>
            <div class="kpi-label">Status</div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>📋 Reconciliation Items</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliation->items as $item)
                    <tr>
                        <td>
                            @if($item->entry)
                                <a href="{{ route('ledger.entries.show', $item->entry) }}">
                                    {{ $item->entry->entry_number }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>${{ number_format($item->amount, 2) }}</td>
                        <td><span class="badge bg-info">{{ $item->type }}</span></td>
                        <td>{{ $item->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No items</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($reconciliation->isLocked())
<div class="alert alert-info mt-4">
    🔒 Locked by {{ $reconciliation->locker?->name ?? 'System' }} on {{ $reconciliation->locked_at->format('F d, Y g:i A') }}.
</div>
@endif

<!-- Lock Modal -->
@if(!$reconciliation->isLocked() && !$reconciliation->hasVariance())
<div class="modal fade" id="lockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('reconciliations.lock', $reconciliation) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">🔒 Lock & Close Day</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will lock all ledger entries for <strong>{{ $reconciliation->reconciliation_date->format('F d, Y') }}</strong>.</p>
                    <p class="text-danger mb-0">⚠️ No further entries can be made for this date after locking.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Lock Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

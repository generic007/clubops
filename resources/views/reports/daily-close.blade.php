@extends('layouts.app')

@section('title', 'Daily Close')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🔒 Daily Close: {{ $date->format('F d, Y') }}</h1>
    <div>
        <a href="{{ route('reports.daily-close', $date->copy()->subDay()->format('Y-m-d')) }}" class="btn btn-outline-secondary">← Previous</a>
        <a href="{{ route('reports.daily-close', $date->copy()->addDay()->format('Y-m-d')) }}" class="btn btn-outline-secondary">Next →</a>
        <a href="{{ route('reports.daily-close', $date->format('Y-m-d')) }}?csv=1" class="btn btn-outline-success">📥 CSV</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.daily-close') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Select Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">View</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">{{ $entryCount }}</div>
            <div class="kpi-label">Entries</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalDebit, 2) }}</div>
            <div class="kpi-label">Total Debits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalCredit, 2) }}</div>
            <div class="kpi-label">Total Credits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalCredit - $totalDebit, 2) }}</div>
            <div class="kpi-label">Net Movement</div>
        </div>
    </div>
</div>

@if($isLocked)
    <div class="alert alert-success">
        ✅ This day is <strong>closed</strong>. All entries have been locked.
    </div>
@else
    <div class="alert alert-warning">
        ⚠️ This day is <strong>open</strong>. Some or all entries are not yet locked.
    </div>
@endif

<!-- Reconciliation Status -->
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>✅ Reconciliation Status</strong></div>
    <div class="card-body">
        @if($reconciliation)
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Reconciliation created</strong>
                    <span class="text-muted ms-2">{{ $reconciliation->created_at->diffForHumans() }}</span>
                    <br>
                    <span>Platform: ${{ number_format($reconciliation->platform_total, 2) }}</span>
                    <span class="ms-3">Ledger: ${{ number_format($reconciliation->ledger_total, 2) }}</span>
                    <span class="ms-3 {{ $reconciliation->hasVariance() ? 'text-danger' : 'text-success' }}">
                        Variance: ${{ number_format($reconciliation->variance, 2) }}
                    </span>
                </div>
                <a href="{{ route('reconciliations.show', $reconciliation) }}" class="btn btn-sm btn-outline-secondary">View</a>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">No reconciliation for this date</span>
                <a href="{{ route('reconciliations.create') }}?date={{ $date->format('Y-m-d') }}" class="btn btn-sm btn-primary">
                    Create Reconciliation
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Entries -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white"><strong>📋 Entries</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Locked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td><a href="{{ route('ledger.entries.show', $entry) }}">{{ $entry->entry_number }}</a></td>
                        <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $entry->type->value) }}</span></td>
                        <td>${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                        <td>${{ number_format($entry->lines->sum('credit'), 2) }}</td>
                        <td>
                            @if($entry->locked)
                                <span class="badge bg-secondary">Locked</span>
                            @else
                                <span class="badge bg-danger">Open</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No entries</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

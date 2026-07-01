@extends('layouts.app')

@section('title', 'Daily Ledger')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 Daily Ledger: {{ $date->format('F d, Y') }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.daily-ledger', $date->copy()->subDay()->format('Y-m-d')) }}" class="btn btn-outline-secondary">← Previous</a>
        <a href="{{ route('reports.daily-ledger', $date->copy()->addDay()->format('Y-m-d')) }}" class="btn btn-outline-secondary">Next →</a>
        <a href="{{ route('reports.daily-ledger', $date->format('Y-m-d')) }}?csv=1" class="btn btn-outline-success">📥 CSV</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.daily-ledger') }}" class="row g-2 align-items-end">
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

<!-- Totals -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">{{ $entries->count() }}</div>
            <div class="kpi-label">Total Entries</div>
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
            <div class="kpi-label">Net</div>
        </div>
    </div>
</div>

<!-- Entries Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Type</th>
                        <th>Description</th>
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
                        <td>{{ \Illuminate\Support\Str::limit($entry->description, 50) }}</td>
                        <td>${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                        <td>${{ number_format($entry->lines->sum('credit'), 2) }}</td>
                        <td>
                            @if($entry->locked)
                                <span class="badge bg-secondary">Locked</span>
                            @else
                                <span class="badge bg-success">Open</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">No entries for this date</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

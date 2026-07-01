@extends('layouts.app')

@section('title', 'Statement - ' . $player->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📋 Player Statement: {{ $player->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('players.show', $player) }}" class="btn btn-outline-secondary">Back to Player</a>
        <a href="{{ route('reports.player-statement', $player) }}?csv=1&from={{ request('from') }}&to={{ request('to') }}"
           class="btn btn-outline-success">📥 CSV</a>
    </div>
</div>

<!-- Balance & Filters -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($balance, 2) }}</div>
            <div class="kpi-label">Current Balance</div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.player-statement', $player) }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statement Entries Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>📋 Ledger Activity</strong>
        <small class="text-muted ms-2">{{ $entries->total() }} entries</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Entry #</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $runningBalance = 0; @endphp
                    @forelse($entries as $line)
                        @php
                            $runningBalance += ($line->credit - $line->debit);
                        @endphp
                    <tr>
                        <td>{{ $line->entry?->entry_date->format('M d, Y') ?? '—' }}</td>
                        <td>
                            @if($line->entry)
                                <a href="{{ route('ledger.entries.show', $line->entry) }}">{{ $line->entry->entry_number }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $line->entry?->description ?? '—' }}</td>
                        <td>${{ number_format($line->debit, 2) }}</td>
                        <td>${{ number_format($line->credit, 2) }}</td>
                        <td class="fw-bold">${{ number_format($runningBalance, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">No activity in this period</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($entries->hasPages())
    <div class="card-footer bg-white">
        {{ $entries->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

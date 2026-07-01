@extends('layouts.app')

@section('title', 'Open Disputes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚖️ Open Disputes</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Player</th>
                        <th>Amount</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td><a href="{{ route('ledger.entries.show', $entry) }}">{{ $entry->entry_number }}</a></td>
                        <td>{{ $entry->lines->first()?->player?->name ?? '—' }}</td>
                        <td>${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                        <td>{{ $entry->creator?->name ?? 'System' }}</td>
                        <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No open disputes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($entries->hasPages())
    <div class="card-footer bg-white">{{ $entries->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

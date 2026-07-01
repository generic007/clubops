@extends('layouts.app')

@section('title', 'Ledger Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 Ledger Entries</h1>
    <a href="{{ route('ledger.entries.create') }}" class="btn btn-primary">+ New Entry</a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', today()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t->value }}" {{ request('type') === $t->value ? 'selected' : '' }}>{{ str_replace('_', ' ', $t->value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Entry #</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Date</th>
                    <th>Locked</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td><a href="{{ route('ledger.entries.show', $entry) }}" class="fw-semibold">{{ $entry->entry_number }}</a></td>
                    <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $entry->type) }}</span></td>
                    <td>{{ \Illuminate\Support\Str::limit($entry->description, 40) }}</td>
                    <td>${{ number_format($entry->lines->sum('debit'), 2) }}</td>
                    <td>${{ number_format($entry->lines->sum('credit'), 2) }}</td>
                    <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                    <td>{!! $entry->locked ? '🔒' : '🔓' !!}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entries->hasPages())
    <div class="card-footer">
        {{ $entries->links() }}
    </div>
    @endif
</div>
@endsection

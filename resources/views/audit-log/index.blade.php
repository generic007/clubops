@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📜 Audit Log</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('audit-log') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Agent</label>
                <select name="agent_id" class="form-select">
                    <option value="">All Agents</option>
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}" {{ request('agent_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Action</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="player_created" {{ request('action') === 'player_created' ? 'selected' : '' }}>Player Created</option>
                    <option value="player_status_changed" {{ request('action') === 'player_status_changed' ? 'selected' : '' }}>Status Change</option>
                    <option value="player_note_created" {{ request('action') === 'player_note_created' ? 'selected' : '' }}>Note Added</option>
                    <option value="ledger_entry_created" {{ request('action') === 'ledger_entry_created' ? 'selected' : '' }}>Ledger Entry</option>
                    <option value="ledger_entry_voided" {{ request('action') === 'ledger_entry_voided' ? 'selected' : '' }}>Entry Voided</option>
                    <option value="reconciliation_created" {{ request('action') === 'reconciliation_created' ? 'selected' : '' }}>Reconciliation</option>
                    <option value="daily_close" {{ request('action') === 'daily_close' ? 'selected' : '' }}>Daily Close</option>
                    <option value="risk_flag_raised" {{ request('action') === 'risk_flag_raised' ? 'selected' : '' }}>Risk Flag</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Action</th>
                        <th>Target</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M d, Y g:i A') }}</td>
                        <td>{{ $log->agent?->name ?? 'System' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ str_replace('_', ' ', $log->action) }}</span>
                        </td>
                        <td>
                            @if($log->auditable_type)
                                <small>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($log->description, 80) ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No audit log entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

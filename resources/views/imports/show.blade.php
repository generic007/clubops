@extends('layouts.app')

@section('title', $import->filename)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 {{ $import->filename }}</h1>
    <div>
        <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $import->total_rows }}</div>
            <div class="kpi-label">Total Rows</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-success">{{ $import->accepted_rows }}</div>
            <div class="kpi-label">Accepted</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-warning">{{ $import->skipped_rows }}</div>
            <div class="kpi-label">Skipped</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-danger">{{ $import->flagged_rows }}</div>
            <div class="kpi-label">Flagged</div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>📋 Rows</strong>
        <span class="badge bg-secondary">{{ $import->type }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Row #</th>
                        <th>Raw Data</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($import->rows as $row)
                    <tr>
                        <td>{{ $row->row_number }}</td>
                        <td>
                            <code class="small">{{ json_encode($row->raw_data) }}</code>
                        </td>
                        <td>
                            @if($row->status === 'accepted')
                                <span class="badge bg-success">Accepted</span>
                            @elseif($row->status === 'skipped')
                                <span class="badge bg-warning">Skipped</span>
                            @elseif($row->status === 'flagged')
                                <span class="badge bg-danger">Flagged</span>
                            @else
                                <span class="badge bg-info">Pending</span>
                            @endif
                        </td>
                        <td>{{ $row->notes ?? '—' }}</td>
                        <td>
                            @if($import->status !== 'completed' && $row->status === 'pending')
                                <form method="POST" action="{{ route('imports.accept', [$import, $row]) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('imports.skip', [$import, $row]) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">Skip</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No rows</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

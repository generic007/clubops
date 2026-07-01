@extends('layouts.app')

@section('title', 'Imports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 Imports</h1>
    <a href="{{ route('imports.create') }}" class="btn btn-primary">+ New Import</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Filename</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Accepted</th>
                        <th>Skipped</th>
                        <th>Flagged</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imports as $import)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $import->type }}</span></td>
                        <td>{{ $import->filename }}</td>
                        <td>
                            @if($import->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($import->status === 'processing')
                                <span class="badge bg-warning">Processing</span>
                            @elseif($import->status === 'flagged')
                                <span class="badge bg-danger">Flagged</span>
                            @else
                                <span class="badge bg-info">{{ ucfirst($import->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $import->total_rows }}</td>
                        <td>{{ $import->accepted_rows }}</td>
                        <td>{{ $import->skipped_rows }}</td>
                        <td>{{ $import->flagged_rows }}</td>
                        <td>{{ $import->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('imports.show', $import) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No imports yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($imports->hasPages())
    <div class="card-footer bg-white">
        {{ $imports->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

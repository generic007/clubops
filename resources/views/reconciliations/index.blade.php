@extends('layouts.app')

@section('title', 'Reconciliations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✅ Reconciliations</h1>
    <a href="{{ route('reconciliations.create') }}" class="btn btn-primary">+ New Reconciliation</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Platform Total</th>
                        <th>Ledger Total</th>
                        <th>Variance</th>
                        <th>Locked At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $recon)
                    <tr>
                        <td>{{ $recon->reconciliation_date->format('M d, Y') }}</td>
                        <td>
                            @if($recon->isLocked())
                                <span class="badge bg-success">Locked</span>
                            @elseif($recon->hasVariance())
                                <span class="badge bg-warning">Variance</span>
                            @else
                                <span class="badge bg-info">Complete</span>
                            @endif
                        </td>
                        <td>${{ number_format($recon->platform_total, 2) }}</td>
                        <td>${{ number_format($recon->ledger_total, 2) }}</td>
                        <td class="{{ $recon->hasVariance() ? 'text-danger fw-bold' : '' }}">
                            ${{ number_format($recon->variance, 2) }}
                        </td>
                        <td>{{ $recon->locked_at?->format('M d, Y g:i A') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('reconciliations.show', $recon) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No reconciliations yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reconciliations->hasPages())
    <div class="card-footer bg-white">
        {{ $reconciliations->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

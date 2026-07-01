@extends('layouts.app')

@section('title', 'Promo Liability')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎁 Promo Liability Report</h1>
    <a href="{{ route('reports.promo-liability') }}?csv=1" class="btn btn-outline-success">📥 CSV</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Promotion</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Cap</th>
                        <th>Claimed</th>
                        <th>Remaining</th>
                        <th>Utilization</th>
                        <th>Period</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                    <tr>
                        <td class="fw-semibold">{{ $promo->name }}</td>
                        <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $promo->type->value) }}</span></td>
                        <td>${{ number_format($promo->value, 2) }}</td>
                        <td>
                            @if($promo->cap)
                                ${{ number_format($promo->cap, 2) }}
                            @else
                                <span class="text-muted">Unlimited</span>
                            @endif
                        </td>
                        <td>${{ number_format($promo->claimed_liability ?? 0, 2) }}</td>
                        <td>
                            @if($promo->cap)
                                ${{ number_format(max(0, $promo->cap - $promo->claimed_liability), 2) }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($promo->cap > 0)
                                @php $pct = min(100, ($promo->claimed_liability / $promo->cap) * 100); @endphp
                                <div class="progress" style="height: 16px;">
                                    <div class="progress-bar bg-{{ $pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success') }}"
                                         style="width: {{ $pct }}%">
                                        {{ number_format($pct, 1) }}%
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $promo->starts_at->format('M d') }}
                                — {{ $promo->ends_at?->format('M d, Y') ?? '∞' }}
                            </small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No active promotions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalCap, 2) }}</div>
            <div class="kpi-label">Total Cap</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalClaimed, 2) }}</div>
            <div class="kpi-label">Total Claimed</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">${{ number_format($totalRemaining, 2) }}</div>
            <div class="kpi-label">Total Remaining</div>
        </div>
    </div>
</div>
@endsection

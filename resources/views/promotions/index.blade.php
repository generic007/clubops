@extends('layouts.app')

@section('title', 'Promotions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎁 Promotions</h1>
    <a href="{{ route('promotions.create') }}" class="btn btn-primary">+ New Promotion</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Total Liability</th>
                        <th>Claimed</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                    <tr>
                        <td class="fw-semibold">{{ $promo->name }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ str_replace('_', ' ', $promo->type->value) }}</span>
                        </td>
                        <td>${{ number_format($promo->value, 2) }}</td>
                        <td>
                            @if($promo->cap)
                                ${{ number_format($promo->cap, 2) }}
                            @else
                                <span class="text-muted">Uncapped</span>
                            @endif
                        </td>
                        <td>${{ number_format($promo->claimed_liability ?? 0, 2) }}</td>
                        <td>
                            {{ $promo->starts_at->format('M d') }}
                            @if($promo->ends_at)
                                — {{ $promo->ends_at->format('M d, Y') }}
                            @else
                                — ∞
                            @endif
                        </td>
                        <td>
                            @if($promo->active && $promo->isActive())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">{{ $promo->active ? 'Scheduled' : 'Inactive' }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('promotions.show', $promo) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('promotions.edit', $promo) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No promotions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($promotions->hasPages())
    <div class="card-footer bg-white">
        {{ $promotions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Activity by Platform')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Activity by Platform</h1>
</div>

<div class="row g-4">
    @forelse($platforms as $platform)
    <div class="col-md-4">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $platform->total }}</div>
            <div class="kpi-label">{{ $platform->platform }}</div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4 text-muted">
                No platform accounts found.
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="display-1 mb-3">🎉</div>
            <h1 class="fw-bold">You're All Set!</h1>
            <p class="fs-5 text-muted mt-3">
                Your subscription is active. Welcome to ClubOps.
            </p>
            @if($plan)
                <p class="badge bg-success fs-6">{{ $plan->name }} Plan</p>
            @endif
            <div class="mt-4">
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection

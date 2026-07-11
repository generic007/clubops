@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Payment Cancelled</h1>
            <p class="fs-5 text-muted mt-3">
                No worries — you weren't charged. You can try again whenever you're ready.
            </p>
            <div class="mt-4">
                <a href="{{ route('billing.index') }}" class="btn btn-outline-primary">Back to Plans</a>
                <a href="{{ url('/dashboard') }}" class="btn btn-primary ms-2">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection

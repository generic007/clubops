@extends('layouts.app')

@section('title', 'Ledger Exceptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚠️ Ledger Exceptions</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @forelse($issues as $issue)
            <div class="alert alert-danger mb-2">
                ⚠️ {{ $issue }}
            </div>
        @empty
            <div class="alert alert-success mb-0">
                ✅ No ledger exceptions found. All entries are balanced.
            </div>
        @endforelse
    </div>
</div>
@endsection

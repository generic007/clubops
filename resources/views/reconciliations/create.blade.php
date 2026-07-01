@extends('layouts.app')

@section('title', 'New Reconciliation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✅ New Reconciliation</h1>
    <a href="{{ route('reconciliations.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('reconciliations.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="reconciliation_date" class="form-control @error('reconciliation_date') is-invalid @enderror"
                           value="{{ old('reconciliation_date', date('Y-m-d')) }}" required>
                    @error('reconciliation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Platform Total <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="platform_total"
                               class="form-control @error('platform_total') is-invalid @enderror"
                               value="{{ old('platform_total') }}" required>
                        @error('platform_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select">
                        <option value="ClubGG" {{ old('platform') === 'ClubGG' ? 'selected' : '' }}>ClubGG</option>
                        <option value="PPPoker" {{ old('platform') === 'PPPoker' ? 'selected' : '' }}>PPPoker</option>
                        <option value="PokerBros" {{ old('platform') === 'PokerBros' ? 'selected' : '' }}>PokerBros</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Screenshot (optional)</label>
                    <input type="file" name="screenshot" class="form-control @error('screenshot') is-invalid @enderror"
                           accept="image/*">
                    @error('screenshot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Reconciliation</button>
                <a href="{{ route('reconciliations.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

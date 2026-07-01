@extends('layouts.app')

@section('title', 'Edit ' . $promotion->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✏️ Edit Promotion: {{ $promotion->name }}</h1>
    <a href="{{ route('promotions.show', $promotion) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('promotions.update', $promotion) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $promotion->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        @foreach(\App\Enums\PromoType::cases() as $t)
                            <option value="{{ $t->value }}" {{ old('type', $promotion->type->value) == $t->value ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($t->value)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $promotion->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Value ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value', $promotion->value) }}" required>
                    @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cap ($) (0 = uncapped)</label>
                    <input type="number" step="0.01" name="cap" class="form-control" value="{{ old('cap', $promotion->cap) }}">
                </div>

                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="active" class="form-check-input" value="1" id="active"
                               {{ old('active', $promotion->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Starts At <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="starts_at"
                           class="form-control @error('starts_at') is-invalid @enderror"
                           value="{{ old('starts_at', $promotion->starts_at->format('Y-m-d\TH:i')) }}" required>
                    @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ends At</label>
                    <input type="datetime-local" name="ends_at" class="form-control"
                           value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea name="terms" class="form-control" rows="3">{{ old('terms', $promotion->terms) }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Promotion</button>
                <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

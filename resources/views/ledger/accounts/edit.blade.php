@extends('layouts.app')

@section('title', 'Edit ' . $account->code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✏️ Edit Account: {{ $account->code }} - {{ $account->name }}</h1>
    <a href="{{ route('ledger.accounts.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('ledger.accounts.update', $account) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $account->code) }}" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $account->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="asset" {{ old('type', $account->type) === 'asset' ? 'selected' : '' }}>Asset</option>
                        <option value="liability" {{ old('type', $account->type) === 'liability' ? 'selected' : '' }}>Liability</option>
                        <option value="equity" {{ old('type', $account->type) === 'equity' ? 'selected' : '' }}>Equity</option>
                        <option value="income" {{ old('type', $account->type) === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type', $account->type) === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency" class="form-control" value="{{ old('currency', $account->currency ?? 'USD') }}" maxlength="3">
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="active" class="form-check-input" value="1" id="active"
                               {{ old('active', $account->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $account->description) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Account</button>
                <a href="{{ route('ledger.accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

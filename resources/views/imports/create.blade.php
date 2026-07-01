@extends('layouts.app')

@section('title', 'New Import')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 New Import</h1>
    <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('imports.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Import Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="players" {{ old('type') === 'players' ? 'selected' : '' }}>Players</option>
                        <option value="ledger" {{ old('type') === 'ledger' ? 'selected' : '' }}>Ledger Entries</option>
                        <option value="game_sessions" {{ old('type') === 'game_sessions' ? 'selected' : '' }}>Game Sessions</option>
                        <option value="promotions" {{ old('type') === 'promotions' ? 'selected' : '' }}>Promotions</option>
                        <option value="tickets" {{ old('type') === 'tickets' ? 'selected' : '' }}>Tickets</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">CSV File <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                           accept=".csv,.tsv,.txt" required>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <strong>Expected columns per type:</strong>
                <ul class="mb-0 small">
                    <li><strong>Players:</strong> name, email, phone, status, agent_email, platform, username</li>
                    <li><strong>Ledger:</strong> entry_date, type, description, account_code, debit, credit, player_email</li>
                    <li><strong>Game Sessions:</strong> player_email, platform, game_name, buy_in, cash_out, session_date</li>
                </ul>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Upload & Preview</button>
                <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

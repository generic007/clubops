@extends('layouts.app')

@section('title', 'New Game')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Schedule Game</h1>
    <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('games.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Game Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="e.g. Friday Night PLO" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Game Type</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select...</option>
                        <option value="PLO" {{ old('type') === 'PLO' ? 'selected' : '' }}>PLO</option>
                        <option value="NLH" {{ old('type') === 'NLH' ? 'selected' : '' }}>NLH</option>
                        <option value="Mixed" {{ old('type') === 'Mixed' ? 'selected' : '' }}>Mixed Game</option>
                        <option value="PLO8" {{ old('type') === 'PLO8' ? 'selected' : '' }}>PLO8</option>
                        <option value="Big O" {{ old('type') === 'Big O' ? 'selected' : '' }}>Big O</option>
                        <option value="Tournament" {{ old('type') === 'Tournament' ? 'selected' : '' }}>Tournament</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stakes</label>
                    <select name="stakes" class="form-select @error('stakes') is-invalid @enderror" required>
                        <option value="">Select...</option>
                        <option value="1/2" {{ old('stakes') === '1/2' ? 'selected' : '' }}>$1/$2</option>
                        <option value="2/5" {{ old('stakes') === '2/5' ? 'selected' : '' }}>$2/$5</option>
                        <option value="5/5" {{ old('stakes') === '5/5' ? 'selected' : '' }}>$5/$5</option>
                        <option value="5/5/10" {{ old('stakes') === '5/5/10' ? 'selected' : '' }}>$5/$5/$10</option>
                        <option value="5/10" {{ old('stakes') === '5/10' ? 'selected' : '' }}>$5/$10</option>
                        <option value="10/20" {{ old('stakes') === '10/20' ? 'selected' : '' }}>$10/$20</option>
                        <option value="25/50" {{ old('stakes') === '25/50' ? 'selected' : '' }}>$25/$50</option>
                    </select>
                    @error('stakes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select @error('platform') is-invalid @enderror" required>
                        <option value="">Select...</option>
                        <option value="ClubGG" {{ old('platform') === 'ClubGG' ? 'selected' : '' }}>ClubGG</option>
                        <option value="PPPoker" {{ old('platform') === 'PPPoker' ? 'selected' : '' }}>PPPoker</option>
                        <option value="PokerBros" {{ old('platform') === 'PokerBros' ? 'selected' : '' }}>PokerBros</option>
                        <option value="Home Game" {{ old('platform') === 'Home Game' ? 'selected' : '' }}>Home Game</option>
                        <option value="Other" {{ old('platform') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('platform')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Scheduled Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror"
                           value="{{ old('scheduled_at') }}" required>
                    @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                              placeholder="Game format, special rules, player invites...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Schedule Game</button>
                <a href="{{ route('games.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

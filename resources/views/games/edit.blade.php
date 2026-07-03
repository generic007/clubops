@extends('layouts.app')

@section('title', 'Edit Game')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✏️ Edit Game</h1>
    <a href="{{ route('games.show', $game) }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('games.update', $game) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Game Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $game->name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="PLO" {{ $game->type === 'PLO' ? 'selected' : '' }}>PLO</option>
                        <option value="NLH" {{ $game->type === 'NLH' ? 'selected' : '' }}>NLH</option>
                        <option value="Mixed" {{ $game->type === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                        <option value="PLO8" {{ $game->type === 'PLO8' ? 'selected' : '' }}>PLO8</option>
                        <option value="Big O" {{ $game->type === 'Big O' ? 'selected' : '' }}>Big O</option>
                        <option value="Tournament" {{ $game->type === 'Tournament' ? 'selected' : '' }}>Tournament</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stakes</label>
                    <select name="stakes" class="form-select" required>
                        <option value="1/2" {{ $game->stakes === '1/2' ? 'selected' : '' }}>$1/$2</option>
                        <option value="2/5" {{ $game->stakes === '2/5' ? 'selected' : '' }}>$2/$5</option>
                        <option value="5/5" {{ $game->stakes === '5/5' ? 'selected' : '' }}>$5/$5</option>
                        <option value="5/5/10" {{ $game->stakes === '5/5/10' ? 'selected' : '' }}>$5/$5/$10</option>
                        <option value="5/10" {{ $game->stakes === '5/10' ? 'selected' : '' }}>$5/$10</option>
                        <option value="10/20" {{ $game->stakes === '10/20' ? 'selected' : '' }}>$10/$20</option>
                        <option value="25/50" {{ $game->stakes === '25/50' ? 'selected' : '' }}>$25/$50</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select" required>
                        <option value="ClubGG" {{ $game->platform === 'ClubGG' ? 'selected' : '' }}>ClubGG</option>
                        <option value="PPPoker" {{ $game->platform === 'PPPoker' ? 'selected' : '' }}>PPPoker</option>
                        <option value="PokerBros" {{ $game->platform === 'PokerBros' ? 'selected' : '' }}>PokerBros</option>
                        <option value="Home Game" {{ $game->platform === 'Home Game' ? 'selected' : '' }}>Home Game</option>
                        <option value="Other" {{ $game->platform === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" {{ $game->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="running" {{ $game->status === 'running' ? 'selected' : '' }}>Running</option>
                        <option value="completed" {{ $game->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $game->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scheduled Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control"
                           value="{{ old('scheduled_at', $game->scheduled_at->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Started At (optional)</label>
                    <input type="datetime-local" name="started_at" class="form-control"
                           value="{{ old('started_at', $game->started_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ended At (optional)</label>
                    <input type="datetime-local" name="ended_at" class="form-control"
                           value="{{ old('ended_at', $game->ended_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $game->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Game</button>
                <a href="{{ route('games.show', $game) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

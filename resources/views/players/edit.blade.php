@extends('layouts.app')

@section('title', $player->exists ? 'Edit Player' : 'Add Player')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $player->exists ? '✏️ Edit Player' : '👤 Add Player' }}</h1>
    <a href="{{ route('players.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ $player->exists ? route('players.update', $player) : route('players.store') }}">
            @csrf
            @if($player->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $player->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Preferred Name</label>
                    <input type="text" name="preferred_name" class="form-control" value="{{ old('preferred_name', $player->preferred_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $player->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $player->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach(App\Enums\PlayerStatus::cases() as $s)
                            <option value="{{ $s->value }}" {{ old('status', $player->status?->value ?? 'lead') === $s->value ? 'selected' : '' }}>{{ ucfirst($s->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referral Source</label>
                    <input type="text" name="referral_source" class="form-control" value="{{ old('referral_source', $player->referral_source) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agent</label>
                    <select name="agent_id" class="form-select">
                        <option value="">None</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id', $player->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned Admin</label>
                    <select name="assigned_admin_id" class="form-select">
                        <option value="">None</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('assigned_admin_id', $player->assigned_admin_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Tags</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    {{ in_array($tag->id, old('tags', $player->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $tag->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preferred Game</label>
                    <select name="preferred_game" class="form-select">
                        <option value="">—</option>
                        <option value="PLO" {{ old('preferred_game', $player->preferred_game) === 'PLO' ? 'selected' : '' }}>PLO</option>
                        <option value="NLH" {{ old('preferred_game', $player->preferred_game) === 'NLH' ? 'selected' : '' }}>NLH</option>
                        <option value="Mixed" {{ old('preferred_game', $player->preferred_game) === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                        <option value="PLO8" {{ old('preferred_game', $player->preferred_game) === 'PLO8' ? 'selected' : '' }}>PLO8</option>
                        <option value="Big O" {{ old('preferred_game', $player->preferred_game) === 'Big O' ? 'selected' : '' }}>Big O</option>
                        <option value="Tournament" {{ old('preferred_game', $player->preferred_game) === 'Tournament' ? 'selected' : '' }}>Tournament</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preferred Stakes</label>
                    <select name="preferred_stakes" class="form-select">
                        <option value="">—</option>
                        <option value="1/2" {{ old('preferred_stakes', $player->preferred_stakes) === '1/2' ? 'selected' : '' }}>$1/$2</option>
                        <option value="2/5" {{ old('preferred_stakes', $player->preferred_stakes) === '2/5' ? 'selected' : '' }}>$2/$5</option>
                        <option value="5/5" {{ old('preferred_stakes', $player->preferred_stakes) === '5/5' ? 'selected' : '' }}>$5/$5</option>
                        <option value="5/5/10" {{ old('preferred_stakes', $player->preferred_stakes) === '5/5/10' ? 'selected' : '' }}>$5/$5/$10</option>
                        <option value="5/10" {{ old('preferred_stakes', $player->preferred_stakes) === '5/10' ? 'selected' : '' }}>$5/$10</option>
                        <option value="10/20" {{ old('preferred_stakes', $player->preferred_stakes) === '10/20' ? 'selected' : '' }}>$10/$20</option>
                    </select>
                </div>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $player->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <h5>🎮 Platform Accounts</h5>
                <div id="platform-accounts">
                    @foreach(old('platform_accounts', $player->platformAccounts->toArray() ?? []) as $i => $acct)
                    <div class="row g-2 mb-2 platform-row">
                        <div class="col-4">
                            <select name="platform_accounts[{{ $i }}][platform]" class="form-select">
                                <option value="ClubGG" {{ ($acct['platform'] ?? '') === 'ClubGG' ? 'selected' : '' }}>ClubGG</option>
                                <option value="PPPoker" {{ ($acct['platform'] ?? '') === 'PPPoker' ? 'selected' : '' }}>PPPoker</option>
                                <option value="PokerBros" {{ ($acct['platform'] ?? '') === 'PokerBros' ? 'selected' : '' }}>PokerBros</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="text" name="platform_accounts[{{ $i }}][username]" class="form-control" placeholder="Username" value="{{ $acct['username'] ?? '' }}">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.platform-row').remove()">✕</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPlatformRow()">+ Add Platform</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ $player->exists ? 'Save Changes' : 'Create Player' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let platformIndex = {{ old('platform_accounts') ? count(old('platform_accounts')) : ($player->platformAccounts->count() ?: 0) }};
function addPlatformRow() {
    const container = document.getElementById('platform-accounts');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 platform-row';
    row.innerHTML = `
        <div class="col-4">
            <select name="platform_accounts[${platformIndex}][platform]" class="form-select">
                <option value="ClubGG">ClubGG</option>
                <option value="PPPoker">PPPoker</option>
                <option value="PokerBros">PokerBros</option>
            </select>
        </div>
        <div class="col-6">
            <input type="text" name="platform_accounts[${platformIndex}][username]" class="form-control" placeholder="Username">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.platform-row').remove()">✕</button>
        </div>`;
    container.appendChild(row);
    platformIndex++;
}
</script>
@endpush
@endsection

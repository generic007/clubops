@extends('layouts.app')

@section('title', 'New Ticket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎫 New Ticket</h1>
    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('tickets.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Player</label>
                    <select name="player_id" class="form-select @error('player_id') is-invalid @enderror">
                        <option value="">Select Player</option>
                        @foreach($players as $p)
                            <option value="{{ $p->id }}" {{ old('player_id', request('player_id')) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('player_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        @foreach(\App\Enums\TicketType::cases() as $t)
                            <option value="{{ $t->value }}" {{ old('type') == $t->value ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($t->value)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                           value="{{ old('subject') }}" required>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                              rows="4">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Ticket</button>
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

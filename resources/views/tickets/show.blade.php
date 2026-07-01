@extends('layouts.app')

@section('title', $ticket->ticket_number . ' - ' . $ticket->subject)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🎫 {{ $ticket->ticket_number }}
        <span class="badge-status badge-{{ $ticket->status->value }} ms-2">{{ ucfirst(str_replace('_', ' ', $ticket->status->value)) }}</span>
    </h1>
    <div>
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <!-- Ticket Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><strong>📋 {{ $ticket->subject }}</strong></div>
            <div class="card-body">
                <p>{{ $ticket->description ?? 'No description provided.' }}</p>

                <dl class="row mb-0 mt-3">
                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9"><span class="badge bg-secondary">{{ str_replace('_', ' ', $ticket->type->value) }}</span></dd>

                    <dt class="col-sm-3">Priority</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">Player</dt>
                    <dd class="col-sm-9">
                        @if($ticket->player)
                            <a href="{{ route('players.show', $ticket->player) }}">{{ $ticket->player->name }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Assigned To</dt>
                    <dd class="col-sm-9">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</dd>

                    <dt class="col-sm-3">Created</dt>
                    <dd class="col-sm-9">{{ $ticket->created_at->format('M d, Y g:i A') }}</dd>

                    @if($ticket->resolved_at)
                    <dt class="col-sm-3">Resolved</dt>
                    <dd class="col-sm-9">{{ $ticket->resolved_at->format('M d, Y g:i A') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Comments Thread -->
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>💬 Comments</strong></div>
            <div class="card-body p-0">
                @forelse($ticket->comments as $comment)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <strong>{{ $comment->author?->name ?? 'System' }}</strong>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 mt-1">{{ $comment->body }}</p>
                    </div>
                @empty
                    <div class="p-3 text-muted">No comments yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Add Comment Form -->
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                    @csrf
                    <div class="mb-2">
                        <textarea name="body" class="form-control" rows="2" placeholder="Add a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Attachments -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><strong>📎 Attachments</strong></div>
            <div class="card-body">
                @forelse($ticket->attachments as $attachment)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">{{ $attachment->original_filename }}</span>
                        <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-outline-primary">Download</a>
                    </div>
                @empty
                    <p class="text-muted mb-0 small">No attachments</p>
                @endforelse
                <hr>
                <form method="POST" action="{{ route('attachments.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="attachable_type" value="{{ get_class($ticket) }}">
                    <input type="hidden" name="attachable_id" value="{{ $ticket->id }}">
                    <div class="mb-2">
                        <input type="file" name="file" class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Upload</button>
                </form>
            </div>
        </div>

        <!-- Status Management -->
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>⚙️ Update Status</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <select name="status" class="form-select form-select-sm">
                            @foreach(\App\Enums\TicketStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ $ticket->status->value == $s->value ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $s->value)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="assigned_to" class="form-select form-select-sm">
                            <option value="">Unassigned</option>
                            @foreach(\App\Models\Agent::where('active', true)->get() as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

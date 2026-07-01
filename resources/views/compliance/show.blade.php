@extends('layouts.app')

@section('title', 'Compliance - ' . $player->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🔒 Compliance Profile: {{ $player->name }}
        @if($player->isExcluded())
            <span class="badge bg-danger">⚠ Excluded</span>
        @endif
    </h1>
    <div>
        @if($player->isExcluded())
            <form method="POST" action="{{ route('compliance.reinstate', $player) }}" class="d-inline"
                  onsubmit="return confirm('Reinstate this player?')">
                @csrf
                <button type="submit" class="btn btn-success">Reinstate Player</button>
            </form>
        @else
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#excludeModal">
                Exclude Player
            </button>
        @endif
        <a href="{{ route('compliance.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📋 Player Info</strong></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $player->name }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge-status badge-{{ $player->status->value }}">{{ ucfirst($player->status->value) }}</span>
                    </dd>

                    <dt class="col-sm-4">Compliance</dt>
                    <dd class="col-sm-8">
                        @if($player->compliance_complete)
                            <span class="badge bg-success">Complete</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Agent</dt>
                    <dd class="col-sm-8">{{ $player->agent?->name ?? '—' }}</dd>

                    <dt class="col-sm-4">Created</dt>
                    <dd class="col-sm-8">{{ $player->created_at->format('M d, Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🪪 ID Verification</strong></div>
            <div class="card-body">
                @if($profile)
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Date of Birth</dt>
                        <dd class="col-sm-8">{{ $profile->date_of_birth?->format('M d, Y') ?? '—' }}</dd>

                        <dt class="col-sm-4">Location</dt>
                        <dd class="col-sm-8">{{ $profile->location ?? '—' }}</dd>

                        <dt class="col-sm-4">ID Status</dt>
                        <dd class="col-sm-8">
                            @if($profile->id_verification_status === 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($profile->id_verification_status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($profile->id_verification_status ?? 'unverified') }}</span>
                            @endif
                        </dd>

                        @if($profile->id_verified_at)
                        <dt class="col-sm-4">Verified At</dt>
                        <dd class="col-sm-8">{{ $profile->id_verified_at->format('M d, Y g:i A') }}</dd>
                        @endif

                        <dt class="col-sm-4">Notes</dt>
                        <dd class="col-sm-8">{{ $profile->compliance_notes ?? '—' }}</dd>
                    </dl>
                @else
                    <p class="text-muted mb-0">No compliance profile created yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Exclusion Timeline -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white"><strong>🚫 Exclusion Timeline</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Starts At</th>
                        <th>Ends At</th>
                        <th>Reason</th>
                        <th>Created By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($player->exclusions as $exclusion)
                    <tr>
                        <td>{{ ucfirst($exclusion->type) }}</td>
                        <td>{{ $exclusion->starts_at->format('M d, Y') }}</td>
                        <td>{{ $exclusion->ends_at?->format('M d, Y') ?? 'Indefinite' }}</td>
                        <td>{{ $exclusion->reason ?? '—' }}</td>
                        <td>{{ $exclusion->createdBy?->name ?? 'System' }}</td>
                        <td>
                            @if($exclusion->isActive())
                                <span class="badge bg-danger">Active</span>
                            @else
                                <span class="badge bg-secondary">Expired</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">No exclusions</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Exclude Modal -->
@if(!$player->isExcluded())
<div class="modal fade" id="excludeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('compliance.exclude', $player) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">🚫 Exclude Player: {{ $player->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will mark the player as excluded and prevent platform access.</p>
                    <div class="mb-3">
                        <label class="form-label">Exclusion Type</label>
                        <select name="type" class="form-select">
                            <option value="temporary">Temporary</option>
                            <option value="permanent">Permanent</option>
                            <option value="self_excluded">Self-Excluded</option>
                            <option value="regulatory">Regulatory</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ends At (leave blank for indefinite)</label>
                        <input type="date" name="ends_at" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Exclude Player</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

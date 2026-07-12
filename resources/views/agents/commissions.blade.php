@extends('layouts.app')

@section('title', 'Commissions')

@section('content')
<div class="container-fluid px-4 py-4" style="max-width: 960px;">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">💵 Commission &amp; Rakeback</h1>
            <p class="text-muted mb-0">{{ $club->name }}</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Structure</button>
    </div>

    <!-- Current Structures -->
    <div class="card">
        <div class="card-header"><strong>Commission Structures</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Type</th>
                            <th>Rate</th>
                            <th>Balance</th>
                            <th>Last Settled</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($structures as $s)
                        <tr>
                            <td class="fw-semibold">{{ $s->agent->name }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ str_replace('_', ' ', $s->type) }}
                                </span>
                            </td>
                            <td>{{ ($s->rate * 100) }}%</td>
                            <td class="amount">${{ number_format($s->agent->commission_balance, 2) }}</td>
                            <td class="text-muted">{{ $s->agent->last_settled_at?->diffForHumans() ?? 'Never' }}</td>
                            <td>
                                @if($s->active)
                                    <span class="text-success">● Active</span>
                                @else
                                    <span class="text-danger">● Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($s->agent->commission_balance > 0)
                                    <form method="POST" action="{{ route('commissions.settle', $s->agent) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" title="Settle">💰 Settle</button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('commissions.destroy', $s) }}" class="d-inline"
                                          onsubmit="return confirm('Remove this commission structure?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">✕</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No commission structures yet. Add one to start tracking rakeback.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('commissions.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Commission Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" class="form-select" required>
                            <option value="">Select agent…</option>
                            @foreach($agents as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} — {{ ucfirst($a->role->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="rakeback_percentage">Rakeback (% of player rake)</option>
                            <option value="flat_fee_per_player">Flat fee per player</option>
                            <option value="volume_tiered">Volume-tiers</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rate (%)</label>
                        <input type="number" name="rate" class="form-control" step="0.1" min="0" max="100"
                               placeholder="e.g. 25 for 25%" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

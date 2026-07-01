@extends('layouts.app')

@section('title', 'Agent Exposure')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🤝 Agent Exposure Report</h1>
    <a href="{{ route('reports.agent-exposure') }}?csv=1" class="btn btn-outline-success">📥 CSV</a>
</div>

<div class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.agent-exposure') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Agent</label>
                    <select name="agent" class="form-select">
                        <option value="">All Agents</option>
                        @foreach($allAgents as $a)
                            <option value="{{ $a->id }}" {{ request('agent') == $a->id ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@forelse($agents as $agent)
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>{{ $agent->name }}</strong>
            <span class="badge bg-{{ $agent->role->value === 'agent' ? 'info' : 'warning' }}">
                {{ ucfirst($agent->role->value) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th>Last Played</th>
                            <th>Risk Status</th>
                            <th>Risk Flags</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $agentBalance = 0; $agentFlags = 0; @endphp
                        @forelse($agent->players as $player)
                            @php
                                $bal = $player->balance();
                                $agentBalance += $bal;
                                $flagCount = $player->riskFlags->where('status', 'open')->count();
                                $agentFlags += $flagCount;
                            @endphp
                        <tr>
                            <td>
                                <a href="{{ route('players.show', $player) }}">{{ $player->name }}</a>
                            </td>
                            <td><span class="badge-status badge-{{ $player->status->value }}">{{ ucfirst($player->status->value) }}</span></td>
                            <td class="{{ $bal < 0 ? 'text-danger' : '' }}">${{ number_format($bal, 2) }}</td>
                            <td>{{ $player->last_played_at?->diffForHumans() ?? 'Never' }}</td>
                            <td>
                                @if($player->risk_status)
                                    <span class="badge bg-{{ $player->risk_status->value === 'high' ? 'danger' : ($player->risk_status->value === 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($player->risk_status->value) }}
                                    </span>
                                @else
                                    <span class="text-muted">Normal</span>
                                @endif
                            </td>
                            <td>{{ $flagCount > 0 ? "🚩 {$flagCount}" : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">No players assigned</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total ({{ $agent->players->count() }} players)</td>
                            <td></td>
                            <td class="{{ $agentBalance < 0 ? 'text-danger' : '' }}">
                                ${{ number_format($agentBalance, 2) }}
                            </td>
                            <td></td>
                            <td></td>
                            <td>{{ $agentFlags > 0 ? "🚩 {$agentFlags}" : '—' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="card shadow-sm">
        <div class="card-body text-center py-4 text-muted">
            No agents found.
        </div>
    </div>
@endforelse
@endsection

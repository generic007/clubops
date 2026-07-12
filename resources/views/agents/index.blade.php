@extends('layouts.app')

@section('title', 'Agents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🤝 Agents</h1>
    <div class="d-flex gap-2 align-items-center">
        @x('export-button', ['route' => route('agents.export')])
        <a href="{{ route('agents.create') }}" class="btn btn-primary">+ New Agent</a>
    </div>
</div>

<!-- Search -->
<div class="d-flex gap-3 align-items-start mb-4 flex-wrap">
    @x('search-bar', ['route' => route('agents.index'), 'placeholder' => 'Search agents...'])
</div>

<!-- Loading Skeleton -->
<div id="loading-agents" x-data x-init="$el.remove()">
    @x('skeleton-table', ['rows' => 5, 'cols' => 7])
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Players</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td class="fw-semibold">{{ $agent->name }}</td>
                        <td>{{ $agent->email }}</td>
                        <td>
                            <span class="badge bg-{{ $agent->role->value === 'owner' ? 'danger' : ($agent->role->value === 'manager' ? 'warning' : 'info') }}">
                                {{ ucfirst($agent->role->value) }}
                            </span>
                        </td>
                        <td>
                            @if($agent->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $agent->players_count ?? $agent->players->count() }}</td>
                        <td>{{ $agent->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No agents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($agents->hasPages())
    <div class="card-footer bg-white">
        {{ $agents->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚙️ Settings</h1>
</div>

<!-- System Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $stats['total_agents'] }}</div>
            <div class="kpi-label">Agents</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $stats['total_players'] }}</div>
            <div class="kpi-label">Players</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $stats['total_accounts'] }}</div>
            <div class="kpi-label">Ledger Accounts</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $stats['total_tags'] }}</div>
            <div class="kpi-label">Tags</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value">{{ $stats['total_templates'] }}</div>
            <div class="kpi-label">Templates</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Agents Overview -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>🤝 Agents</strong>
                <a href="{{ route('agents.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Name</th><th>Role</th><th>Players</th><th>Active</th></tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                        <tr>
                            <td>{{ $agent->name }}</td>
                            <td><span class="badge bg-secondary">{{ $agent->role->value }}</span></td>
                            <td>{{ $agent->players_count }}</td>
                            <td>
                                @if($agent->active)
                                    <span class="text-success">✅</span>
                                @else
                                    <span class="text-danger">❌</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart of Accounts -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>📊 Chart of Accounts</strong>
                <a href="{{ route('ledger.accounts.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Code</th><th>Name</th><th>Type</th><th>Balance</th></tr>
                    </thead>
                    <tbody>
                        @foreach($ledgerAccounts as $account)
                        <tr>
                            <td class="font-mono">{{ $account->code }}</td>
                            <td>{{ $account->name }}</td>
                            <td><span class="badge bg-info">{{ $account->type }}</span></td>
                            <td class="amount">${{ number_format($account->balance, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>ℹ️ System Information</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="label-uppercase">PHP Version</div>
                        <div class="fw-semibold">{{ phpversion() }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Laravel Version</div>
                        <div class="fw-semibold">{{ app()->version() }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Environment</div>
                        <div class="fw-semibold">{{ app()->environment() }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Database</div>
                        <div class="fw-semibold">{{ config('database.default') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">App URL</div>
                        <div class="fw-semibold">{{ config('app.url') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Queue Driver</div>
                        <div class="fw-semibold">{{ config('queue.default') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Note -->
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header text-warning bg-warning bg-opacity-10">
                <strong>⚖️ Compliance & Safety</strong>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>No payment processing.</strong> The system tracks operational ledgers only.</li>
                    <li><strong>No automatic chip loading or cashout.</strong> No integration with payment gateways.</li>
                    <li><strong>No scraping or botting</strong> of poker platform apps.</li>
                    <li><strong>No evasion</strong> of platform terms of service.</li>
                    <li><strong>All ledgers are auditable.</strong> Every entry has an actor, timestamp, and reason.</li>
                    <li><strong>Responsible gaming:</strong> cool-off, self-exclusion, and admin suspension are first-class features.</li>
                    <li><strong>No permanent deletion</strong> of ledger records. Corrections use reversal entries.</li>
                    <li><strong>Audit logs</strong> capture all sensitive actions.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚙️ Settings</h1>
</div>

<!-- Club Info -->
<div class="card mb-4">
    <div class="card-header">
        <strong>🏛️ {{ $club->name }}</strong>
        <span class="badge bg-success">Active</span>
        @if($club->single_club)
            <span class="badge bg-info">Single Club</span>
        @endif
        <span class="badge bg-warning text-dark" style="margin-left:4px;">🔒 E2E Encrypted</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="label-uppercase">Club Name</div>
                <div class="fw-semibold">{{ $club->name }}</div>
            </div>
            <div class="col-md-4">
                <div class="label-uppercase">Contact Email</div>
                <div class="fw-semibold">{{ $club->contact_email ?? '—' }}</div>
            </div>
            <div class="col-md-4">
                <div class="label-uppercase">Timezone</div>
                <div class="fw-semibold">{{ $club->timezone }}</div>
            </div>
            @if($club->description)
            <div class="col-12">
                <div class="label-uppercase">Description</div>
                <div class="fw-semibold">{{ $club->description }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(\App\ClubOpsEdition::isPro())
<!-- Player Portal -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>🃏 Player Portal</strong>
        <a href="{{ route('player.login') }}" target="_blank" class="btn btn-sm btn-outline-success">View Portal →</a>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            Players with portal access can <strong>log in at {{ route('player.login') }}</strong> to see their balance,
            transaction history, active promotions, and support tickets — nothing else.
        </p>

        @if($playersWithPortal->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Email</th>
                            <th>Last Login</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($playersWithPortal as $p)
                            <tr>
                                <td><a href="{{ route('players.show', $p) }}">{{ $p->name }}</a></td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                                <td class="amount">${{ number_format($p->balance(), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-3">
                <p class="text-muted mb-2">No players have portal access yet.</p>
                <a href="{{ route('players.index') }}" class="btn btn-sm btn-primary">Enable Portal for a Player</a>
            </div>
        @endif
    </div>
</div>
@endif

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

    <!-- Zero-Trust Encryption -->
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header text-info bg-info bg-opacity-10">
                <strong>🔒 Zero-Trust Encryption</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="label-uppercase">Encryption Method</div>
                        <div class="fw-semibold">AES-256-GCM + Argon2id</div>
                        <div class="text-muted" style="font-size:.82rem;">
                            Each club has a unique 256-bit master key. The key is encrypted
                            with the owner's password using Argon2id key derivation.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="label-uppercase">Data at Rest</div>
                        <div class="fw-semibold">Fully Encrypted</div>
                        <div class="text-muted" style="font-size:.82rem;">
                            Player names, agent names, contact info, notes, and other PII
                            are encrypted before they touch the database. Even the server
                            operator cannot read them without the club key.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="label-uppercase">Key Storage</div>
                        <div class="fw-semibold">Encrypted with Password</div>
                        <div class="text-muted" style="font-size:.82rem;">
                            The club key is stored encrypted in the database. It is only
                            decrypted for the duration of an authenticated session.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="label-uppercase">Key Recovery</div>
                        <div class="fw-semibold">Password-Dependent</div>
                        <div class="text-muted" style="font-size:.82rem;">
                            If the owner's password is lost, <strong>the data cannot be recovered</strong>.
                            This is by design — the server has no backdoor.
                        </div>
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

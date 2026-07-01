<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>@yield('title', 'ClubOps OS') — ClubOps</title>
    <meta name="description" content="Private poker club operations system">
    <meta name="theme-color" content="#2563eb">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --bottom-nav-height: 60px;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100dvh;
        }
        /* Layout */
        .app-layout { display: flex; min-height: 100dvh; }
        .sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100dvh;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 20px 16px;
            font-size: 1.2rem;
            font-weight: 800;
            background: #0f172a;
            border-bottom: 1px solid #334155;
        }
        .sidebar-brand a { color: #3b82f6; text-decoration: none; }
        .sidebar-brand small { font-weight: 400; color: #64748b; font-size: 0.7rem; }
        .sidebar-nav { flex: 1; padding: 8px 0; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.15s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: #334155; color: #e2e8f0; }
        .sidebar-nav a.active { border-left: 3px solid #3b82f6; }
        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid #334155;
            font-size: 0.8rem;
            color: #64748b;
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px 24px;
            padding-bottom: calc(20px + var(--bottom-nav-height));
            width: calc(100% - var(--sidebar-width));
        }
        /* Mobile bottom nav */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: var(--bottom-nav-height);
            background: #fff;
            border-top: 1px solid #e2e8f0;
            z-index: 200;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.06);
        }
        body.dark .bottom-nav { background: #1e293b; border-color: #334155; }
        .bottom-nav-inner {
            display: flex;
            height: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 600;
        }
        .bottom-nav a.active { color: #2563eb; }
        .bottom-nav a .nav-icon { font-size: 1.3rem; }

        /* Cards */
        .kpi-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s;
        }
        body.dark .kpi-card { background: #1e293b; }
        .kpi-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .kpi-value { font-size: 1.8rem; font-weight: 800; line-height: 1.2; }
        .kpi-label { font-size: 0.8rem; color: #64748b; margin-top: 4px; }
        .kpi-icon { font-size: 1.5rem; }

        /* Tables */
        .table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .table td { vertical-align: middle; }

        /* Status badges */
        .badge-status { font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-lead { background: #fef9c3; color: #854d0e; }
        .badge-pending { background: #dbeafe; color: #1e40af; }
        .badge-inactive { background: #f1f5f9; color: #475569; }
        .badge-vip { background: #fef08a; color: #713f12; }
        .badge-suspended, .badge-banned, .badge-excluded { background: #fee2e2; color: #991b1b; }
        .badge-open { background: #dbeafe; color: #1e40af; }
        .badge-closed { background: #f1f5f9; color: #475569; }

        /* Forms */
        .form-label { font-weight: 600; font-size: 0.85rem; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 14px; }

        /* Buttons */
        .btn { border-radius: 8px; padding: 8px 18px; font-weight: 600; }

        /* Flash */
        .alert-flash {
            position: fixed;
            top: 16px; right: 16px;
            z-index: 9999;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Responsive */
        @media (max-width: 767px) {
            .sidebar { display: none; }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 12px 16px;
                padding-bottom: calc(var(--bottom-nav-height) + 12px);
            }
            .bottom-nav { display: block; }
            .kpi-value { font-size: 1.4rem; }
        }
        @media (min-width: 768px) {
            .mobile-only { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        <!-- Desktop Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}">♠️ ClubOps <small>OS</small></a>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                <a href="{{ route('players.index') }}" class="{{ request()->routeIs('players.*') ? 'active' : '' }}">👥 Players</a>
                <a href="{{ route('agents.index') }}" class="{{ request()->routeIs('agents.*') ? 'active' : '' }}">🤝 Agents</a>
                <hr style="border-color:#334155;margin:8px 16px;">
                <a href="{{ route('ledger.entries.index') }}" class="{{ request()->routeIs('ledger.*') ? 'active' : '' }}">💰 Ledger</a>
                <a href="{{ route('reconciliations.index') }}" class="{{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">✅ Reconciliation</a>
                <hr style="border-color:#334155;margin:8px 16px;">
                <a href="{{ route('promotions.index') }}" class="{{ request()->routeIs('promotions.*') ? 'active' : '' }}">🎁 Promotions</a>
                <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">🎫 Tickets</a>
                <a href="{{ route('imports.index') }}" class="{{ request()->routeIs('imports.*') ? 'active' : '' }}">📥 Imports</a>
                <hr style="border-color:#334155;margin:8px 16px;">
                <a href="{{ route('reports.player-statement', 1) }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">📋 Reports</a>
                <a href="{{ route('compliance.index') }}" class="{{ request()->routeIs('compliance.*') ? 'active' : '' }}">🔒 Compliance</a>
                <a href="{{ route('audit-log') }}" class="{{ request()->routeIs('audit-log') ? 'active' : '' }}">📜 Audit Log</a>
            </nav>
            <div class="sidebar-footer">
                <div>{{ auth()->user()->name ?? 'Agent' }}</div>
                <div style="font-size:0.7rem;">{{ auth()->user()->role->value ?? '' }}</div>
                <form method="POST" action="{{ route('logout') }}" style="margin-top:6px;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#ef4444;padding:0;font-size:0.8rem;cursor:pointer;">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show alert-flash" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show alert-flash" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Nav -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Home
            </a>
            <a href="{{ route('players.index') }}" class="{{ request()->routeIs('players.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Players
            </a>
            <a href="{{ route('ledger.entries.index') }}" class="{{ request()->routeIs('ledger.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span> Ledger
            </a>
            <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                <span class="nav-icon">🎫</span> Tickets
            </a>
            <a href="{{ route('compliance.index') }}" class="{{ request()->routeIs('compliance.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> More
            </a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss flash alerts
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert-flash').forEach(function(el) {
                setTimeout(function() { el.remove(); }, 5000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

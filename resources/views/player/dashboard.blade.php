<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard — ClubOps</title>
    <meta name="theme-color" content="#064e3b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --primary-dark: #059669; --accent: #0d9488; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0fdf4;
            margin: 0;
            min-height: 100dvh;
        }
        .navbar {
            background: linear-gradient(135deg, #064e3b, #0d9488);
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        .navbar .brand { font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; }
        .navbar .user-info { font-size: 0.85rem; opacity: 0.9; display: flex; align-items: center; gap: 16px; }
        .navbar a { color: #fff; text-decoration: none; font-weight: 600; }
        .container { max-width: 800px; margin: 0 auto; padding: 24px 16px; }

        .balance-card {
            background: linear-gradient(135deg, #064e3b, #0d9488);
            border-radius: 16px;
            padding: 28px;
            color: #fff;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(6,78,59,.2);
        }
        .balance-card .label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; opacity: 0.8; }
        .balance-card .amount { font-size: 2.2rem; font-weight: 800; margin: 4px 0; }
        .balance-card .sub { font-size: 0.85rem; opacity: 0.8; }

        .stats-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 24px; }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .stat-card .stat-value { font-size: 1.3rem; font-weight: 700; color: #064e3b; }
        .stat-card .stat-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px; }

        .section-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 20px 0 12px; display: flex; align-items: center; gap: 8px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.06); margin-bottom: 16px; overflow: hidden; }
        .card-body { padding: 16px 20px; }

        .entry-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .entry-row:last-child { border-bottom: none; }
        .entry-row .desc { font-size: 0.85rem; color: #1e293b; }
        .entry-row .date { font-size: 0.75rem; color: #94a3b8; }
        .entry-row .amount { font-weight: 700; font-size: 0.9rem; }
        .entry-row .amount.credit { color: var(--primary); }
        .entry-row .amount.debit { color: #ef4444; }

        .empty-state { text-align: center; padding: 32px 20px; color: #94a3b8; }
        .empty-state .icon { font-size: 2rem; margin-bottom: 8px; }

        .badge-promo { display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }

        .logout-form { display: inline; }
        .logout-btn { background: none; border: 1px solid rgba(255,255,255,.3); color: #fff; padding: 6px 16px; border-radius: 9999px; font-size: 0.8rem; cursor: pointer; }
        .logout-btn:hover { background: rgba(255,255,255,.1); }

        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .navbar { flex-direction: column; gap: 8px; text-align: center; }
        }
    </style>
</head>
<body>
    <!-- Nav -->
    <nav class="navbar">
        <div class="brand">♠ ClubOps</div>
        <div class="user-info">
            <span>👤 {{ $player->preferred_name ?? $player->name }}</span>
            <form method="POST" action="{{ route('player.logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Sign Out</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Balance -->
        <div class="balance-card">
            <div class="label">Current Balance</div>
            <div class="amount">${{ number_format($player->balance(), 2) }}</div>
            <div class="sub">Lifetime Volume: ${{ number_format($player->lifetimeVolume(), 2) }}</div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $recentTickets->where('status', 'open')->count() }}</div>
                <div class="stat-label">Open Tickets</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $activePromos->count() }}</div>
                <div class="stat-label">Active Promos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $player->last_played_at?->diffForHumans() ?? 'Never' }}</div>
                <div class="stat-label">Last Played</div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="section-title">📒 Recent Activity</div>
        <div class="card">
            <div class="card-body">
                @forelse($recentEntries as $line)
                    <div class="entry-row">
                        <div>
                            <div class="desc">{{ $line->entry->description ?? 'Transaction' }}</div>
                            <div class="date">{{ $line->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                        <div class="amount {{ $line->credit > 0 ? 'credit' : 'debit' }}">
                            {{ $line->credit > 0 ? '+' : '-' }}${{ number_format($line->credit > 0 ? $line->credit : $line->debit, 2) }}
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <p>No transactions yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Active Promotions -->
        @if($activePromos->count() > 0)
            <div class="section-title">🎁 Active Promotions</div>
            <div class="card">
                <div class="card-body">
                    @foreach($activePromos as $redemption)
                        <div class="entry-row">
                            <div>
                                <div class="desc">{{ $redemption->promotion->name }}</div>
                                <div class="date">Claimed {{ $redemption->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="badge-promo">Active</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Tickets -->
        @if($recentTickets->count() > 0)
            <div class="section-title">🎫 Support Tickets</div>
            <div class="card">
                <div class="card-body">
                    @foreach($recentTickets as $ticket)
                        <div class="entry-row">
                            <div>
                                <div class="desc">{{ $ticket->subject }}</div>
                                <div class="date">{{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'resolved' ? 'success' : 'secondary') }} text-dark">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Your Info -->
        <div class="section-title">👤 My Profile</div>
        <div class="card">
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.9rem;">
                    <div><strong>Name:</strong> {{ $player->name }}</div>
                    <div><strong>Email:</strong> {{ $player->email ?? '—' }}</div>
                    <div><strong>Preferred Game:</strong> {{ $player->preferred_game ?? '—' }}</div>
                    <div><strong>Preferred Stakes:</strong> {{ $player->preferred_stakes ?? '—' }}</div>
                    <div><strong>Member Since:</strong> {{ $player->created_at->format('M j, Y') }}</div>
                    <div><strong>Agent:</strong> {{ $player->agent?->name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

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
            <span>👤 <?php echo e($player->preferred_name ?? $player->name); ?></span>
            <form method="POST" action="<?php echo e(route('player.logout')); ?>" class="logout-form">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn">Sign Out</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Balance -->
        <div class="balance-card">
            <div class="label">Current Balance</div>
            <div class="amount">$<?php echo e(number_format($player->balance(), 2)); ?></div>
            <div class="sub">Lifetime Volume: $<?php echo e(number_format($player->lifetimeVolume(), 2)); ?></div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo e($recentTickets->where('status', 'open')->count()); ?></div>
                <div class="stat-label">Open Tickets</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo e($activePromos->count()); ?></div>
                <div class="stat-label">Active Promos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo e($player->last_played_at?->diffForHumans() ?? 'Never'); ?></div>
                <div class="stat-label">Last Played</div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="section-title">📒 Recent Activity</div>
        <div class="card">
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="entry-row">
                        <div>
                            <div class="desc"><?php echo e($line->entry->description ?? 'Transaction'); ?></div>
                            <div class="date"><?php echo e($line->created_at->format('M j, Y g:i A')); ?></div>
                        </div>
                        <div class="amount <?php echo e($line->credit > 0 ? 'credit' : 'debit'); ?>">
                            <?php echo e($line->credit > 0 ? '+' : '-'); ?>$<?php echo e(number_format($line->credit > 0 ? $line->credit : $line->debit, 2)); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <p>No transactions yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Active Promotions -->
        <?php if($activePromos->count() > 0): ?>
            <div class="section-title">🎁 Active Promotions</div>
            <div class="card">
                <div class="card-body">
                    <?php $__currentLoopData = $activePromos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $redemption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="entry-row">
                            <div>
                                <div class="desc"><?php echo e($redemption->promotion->name); ?></div>
                                <div class="date">Claimed <?php echo e($redemption->created_at->diffForHumans()); ?></div>
                            </div>
                            <span class="badge-promo">Active</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recent Tickets -->
        <?php if($recentTickets->count() > 0): ?>
            <div class="section-title">🎫 Support Tickets</div>
            <div class="card">
                <div class="card-body">
                    <?php $__currentLoopData = $recentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="entry-row">
                            <div>
                                <div class="desc"><?php echo e($ticket->subject); ?></div>
                                <div class="date"><?php echo e($ticket->created_at->diffForHumans()); ?></div>
                            </div>
                            <span class="badge bg-<?php echo e($ticket->status === 'open' ? 'warning' : ($ticket->status === 'resolved' ? 'success' : 'secondary')); ?> text-dark">
                                <?php echo e(ucfirst($ticket->status)); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Your Info -->
        <div class="section-title">👤 My Profile</div>
        <div class="card">
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.9rem;">
                    <div><strong>Name:</strong> <?php echo e($player->name); ?></div>
                    <div><strong>Email:</strong> <?php echo e($player->email ?? '—'); ?></div>
                    <div><strong>Preferred Game:</strong> <?php echo e($player->preferred_game ?? '—'); ?></div>
                    <div><strong>Preferred Stakes:</strong> <?php echo e($player->preferred_stakes ?? '—'); ?></div>
                    <div><strong>Member Since:</strong> <?php echo e($player->created_at->format('M j, Y')); ?></div>
                    <div><strong>Agent:</strong> <?php echo e($player->agent?->name ?? '—'); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/player/dashboard.blade.php ENDPATH**/ ?>
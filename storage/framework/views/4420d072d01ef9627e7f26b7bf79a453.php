<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚙️ Settings</h1>
</div>

<!-- Club Info -->
<div class="card mb-4">
    <div class="card-header">
        <strong>🏛️ <?php echo e($club->name); ?></strong>
        <span class="badge bg-success">Active</span>
        <?php if($club->single_club): ?>
            <span class="badge bg-info">Single Club</span>
        <?php endif; ?>
        <span class="badge bg-warning text-dark" style="margin-left:4px;">🔒 E2E Encrypted</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="label-uppercase">Club Name</div>
                <div class="fw-semibold"><?php echo e($club->name); ?></div>
            </div>
            <div class="col-md-4">
                <div class="label-uppercase">Contact Email</div>
                <div class="fw-semibold"><?php echo e($club->contact_email ?? '—'); ?></div>
            </div>
            <div class="col-md-4">
                <div class="label-uppercase">Timezone</div>
                <div class="fw-semibold"><?php echo e($club->timezone); ?></div>
            </div>
            <?php if($club->description): ?>
            <div class="col-12">
                <div class="label-uppercase">Description</div>
                <div class="fw-semibold"><?php echo e($club->description); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(\App\ClubOpsEdition::isPro()): ?>
<!-- Player Portal -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>🃏 Player Portal</strong>
        <a href="<?php echo e(route('player.login')); ?>" target="_blank" class="btn btn-sm btn-outline-success">View Portal →</a>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            Players with portal access can <strong>log in at <?php echo e(route('player.login')); ?></strong> to see their balance,
            transaction history, active promotions, and support tickets — nothing else.
        </p>

        <?php if($playersWithPortal->count() > 0): ?>
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
                        <?php $__currentLoopData = $playersWithPortal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('players.show', $p)); ?>"><?php echo e($p->name); ?></a></td>
                                <td><?php echo e($p->email); ?></td>
                                <td><?php echo e($p->last_login_at?->diffForHumans() ?? 'Never'); ?></td>
                                <td class="amount">$<?php echo e(number_format($p->balance(), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state py-3">
                <p class="text-muted mb-2">No players have portal access yet.</p>
                <a href="<?php echo e(route('players.index')); ?>" class="btn btn-sm btn-primary">Enable Portal for a Player</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- System Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($stats['total_agents']); ?></div>
            <div class="kpi-label">Agents</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($stats['total_players']); ?></div>
            <div class="kpi-label">Players</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($stats['total_accounts']); ?></div>
            <div class="kpi-label">Ledger Accounts</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($stats['total_tags']); ?></div>
            <div class="kpi-label">Tags</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($stats['total_templates']); ?></div>
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
                <a href="<?php echo e(route('agents.index')); ?>" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Name</th><th>Role</th><th>Players</th><th>Active</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($agent->name); ?></td>
                            <td><span class="badge bg-secondary"><?php echo e($agent->role->value); ?></span></td>
                            <td><?php echo e($agent->players_count); ?></td>
                            <td>
                                <?php if($agent->active): ?>
                                    <span class="text-success">✅</span>
                                <?php else: ?>
                                    <span class="text-danger">❌</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <a href="<?php echo e(route('ledger.accounts.index')); ?>" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Code</th><th>Name</th><th>Type</th><th>Balance</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ledgerAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="font-mono"><?php echo e($account->code); ?></td>
                            <td><?php echo e($account->name); ?></td>
                            <td><span class="badge bg-info"><?php echo e($account->type); ?></span></td>
                            <td class="amount">$<?php echo e(number_format($account->balance, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="fw-semibold"><?php echo e(phpversion()); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Laravel Version</div>
                        <div class="fw-semibold"><?php echo e(app()->version()); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Environment</div>
                        <div class="fw-semibold"><?php echo e(app()->environment()); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Database</div>
                        <div class="fw-semibold"><?php echo e(config('database.default')); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">App URL</div>
                        <div class="fw-semibold"><?php echo e(config('app.url')); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label-uppercase">Queue Driver</div>
                        <div class="fw-semibold"><?php echo e(config('queue.default')); ?></div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/settings/index.blade.php ENDPATH**/ ?>
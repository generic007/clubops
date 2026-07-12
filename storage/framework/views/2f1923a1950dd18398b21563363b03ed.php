<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1"><?php echo e($club->name); ?></h1>
            <p class="text-muted mb-0">
                <?php echo e(now()->format('l, F j, Y')); ?> &middot;
                <?php echo e($activePlayers); ?> active players
                <?php if($newThisWeek > 0): ?>
                    &middot; <span class="text-success">+<?php echo e($newThisWeek); ?> new this week</span>
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#buyInModal">
                💰 Buy-In
            </button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cashOutModal">
                💸 Cash-Out
            </button>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon">👥</div>
                <div>
                    <div class="kpi-value"><?php echo e($totalPlayers); ?></div>
                    <div class="kpi-label">Total Players</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(16,185,129,.1);">🟢</div>
                <div>
                    <div class="kpi-value"><?php echo e($activePlayers); ?></div>
                    <div class="kpi-label">Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(245,158,11,.1);">📊</div>
                <div>
                    <div class="kpi-value">$<?php echo e(number_format($todayVolume, 0)); ?></div>
                    <div class="kpi-label">Today's Volume</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background: rgba(239,68,68,.1);">🎫</div>
                <div>
                    <div class="kpi-value"><?php echo e($openTickets); ?></div>
                    <div class="kpi-label">Open Tickets</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity + Quick Actions -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <strong>📒 Recent Activity</strong>
                <a href="<?php echo e(route('ledger.entries.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if($recentEntries->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('players.show', $entry->player)); ?>" class="fw-semibold">
                                            <?php echo e($entry->player?->name ?? '—'); ?>

                                        </a>
                                    </td>
                                    <td><span class="badge bg-info text-dark"><?php echo e(str_replace('_', ' ', $entry->type->value)); ?></span></td>
                                    <td class="amount">
                                        <?php
                                            $line = $entry->lines->first();
                                        ?>
                                        <?php if($line): ?>
                                            <span class="<?php echo e($line->debit > 0 ? 'text-danger' : ($line->credit > 0 ? 'text-success' : '')); ?>">
                                                <?php if($line->debit > 0): ?>-$<?php echo e(number_format($line->debit, 2)); ?><?php else: ?> +$<?php echo e(number_format($line->credit, 2)); ?><?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted" style="white-space:nowrap;"><?php echo e($entry->created_at->diffForHumans()); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-title">No activity yet</div>
                        <div class="empty-text">Record your first buy-in or cash-out to get started.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Stats -->
        <div class="card mb-3">
            <div class="card-header"><strong>⚡ Quick Actions</strong></div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('players.create')); ?>" class="btn btn-outline-primary">
                        ➕ Add Player
                    </a>
                    <a href="<?php echo e(route('ledger.entries.create')); ?>" class="btn btn-outline-info">
                        📝 Record Entry
                    </a>
                    <a href="<?php echo e(route('reconciliations.create')); ?>" class="btn btn-outline-warning">
                        ✅ Run Reconciliation
                    </a>
                    <?php if(\App\ClubOpsEdition::isPro()): ?>
                    <a href="<?php echo e(route('invitations.index')); ?>" class="btn btn-outline-success">
                        👤 Invite Team Member
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Players -->
        <?php if($recentPlayers->count() > 0): ?>
        <div class="card">
            <div class="card-header"><strong>🎮 Recent Players</strong></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $recentPlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('players.show', $p)); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold"><?php echo e($p->name); ?></div>
                            <small class="text-muted">Last played <?php echo e($p->last_played_at->diffForHumans()); ?></small>
                        </div>
                        <span class="badge bg-<?php echo e($p->status->value === 'active' ? 'success' : 'secondary'); ?> text-dark">
                            <?php echo e(ucfirst($p->status->value)); ?>

                        </span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Buy-In Modal -->
<div class="modal fade" id="buyInModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('quick.buy-in')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">💰 Record Buy-In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select a player…</option>
                            <?php $__currentLoopData = $quickPlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> <?php if($p->email): ?>(<?php echo e($p->email); ?>)<?php endif; ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buy-In Amount ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="e.g. 200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <small class="text-muted">(optional)</small></label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. $1/$2 NLH">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">💰 Record Buy-In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cash-Out Modal -->
<div class="modal fade" id="cashOutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('quick.cash-out')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">💸 Record Cash-Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select a player…</option>
                            <?php $__currentLoopData = $quickPlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> <?php if($p->email): ?>(<?php echo e($p->email); ?>)<?php endif; ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cash-Out Amount ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="e.g. 350" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <small class="text-muted">(optional)</small></label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. $1/$2 NLH — cashed for $350">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">💸 Record Cash-Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/dashboard/index.blade.php ENDPATH**/ ?>
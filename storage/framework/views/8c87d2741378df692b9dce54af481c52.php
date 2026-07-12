<?php $__env->startSection('title', $game->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><?php echo e($game->name); ?></h1>
        <div class="text-muted">
            <?php echo e($game->type); ?> / <?php echo e($game->stakes); ?> · <?php echo e($game->platform); ?>

        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('games.edit', $game)); ?>" class="btn btn-outline-primary">Edit</a>
        <a href="<?php echo e(route('games.index')); ?>" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

<!-- Game Info -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value"><?php
                $statusColors = ['scheduled' => '#3b82f6', 'running' => '#10b981', 'completed' => '#64748b', 'cancelled' => '#ef4444'];
            ?>
            <span style="color: <?php echo e($statusColors[$game->status] ?? '#64748b'); ?>"><?php echo e(ucfirst($game->status)); ?></span></div>
            <div class="kpi-label">Status</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value"><?php echo e($playerCount); ?></div>
            <div class="kpi-label">Players</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalBuyins, 0)); ?></div>
            <div class="kpi-label">Total Buy-ins</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalCashouts, 0)); ?></div>
            <div class="kpi-label">Total Cash-outs</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Game Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>📋 Game Details</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Name</td>
                        <td class="fw-semibold"><?php echo e($game->name); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td><?php echo e($game->type); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Stakes</td>
                        <td><?php echo e($game->stakes); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Platform</td>
                        <td><span class="badge bg-secondary"><?php echo e($game->platform); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Scheduled</td>
                        <td><?php echo e($game->scheduled_at->format('l, F j, Y g:i A')); ?></td>
                    </tr>
                    <?php if($game->started_at): ?>
                    <tr>
                        <td class="text-muted">Started</td>
                        <td><?php echo e($game->started_at->format('M d, g:i A')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($game->ended_at): ?>
                    <tr>
                        <td class="text-muted">Ended</td>
                        <td><?php echo e($game->ended_at->format('M d, g:i A')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Created by</td>
                        <td><?php echo e($game->creator?->name ?? '—'); ?></td>
                    </tr>
                </table>
                <?php if($game->notes): ?>
                <hr>
                <div class="text-muted small">Notes</div>
                <p class="mb-0"><?php echo e($game->notes); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Player Sessions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>👥 Player Sessions</strong>
                <?php if(in_array($game->status, ['scheduled', 'running'])): ?>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                    + Add Player
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Player</th><th>Buy-in</th><th>Cash-out</th><th>P/L</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $game->sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($session->player?->name ?? 'Unknown'); ?></td>
                            <td class="amount">$<?php echo e(number_format($session->buy_in, 2)); ?></td>
                            <td class="amount"><?php echo e($session->cash_out ? '$'.number_format($session->cash_out, 2) : '—'); ?></td>
                            <td class="amount <?php echo e($session->profit_loss && $session->profit_loss > 0 ? 'text-success' : ($session->profit_loss && $session->profit_loss < 0 ? 'text-danger' : '')); ?>">
                                <?php echo e($session->profit_loss !== null ? '$'.number_format($session->profit_loss, 2) : '—'); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center py-3 text-muted">No players added yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Session Modal -->
<?php if(in_array($game->status, ['scheduled', 'running'])): ?>
<div class="modal fade" id="addSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('games.sessions.start', $game)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Player to Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Player</label>
                        <select name="player_id" class="form-select" required>
                            <option value="">Select player...</option>
                            <?php $__currentLoopData = \App\Models\Player::active()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buy-in Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="buy_in" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Player</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/games/show.blade.php ENDPATH**/ ?>
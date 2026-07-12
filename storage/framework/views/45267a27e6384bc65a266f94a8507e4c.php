<?php $__env->startSection('title', $promotion->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🎁 <?php echo e($promotion->name); ?>

        <?php if($promotion->isActive()): ?>
            <span class="badge bg-success">Active</span>
        <?php else: ?>
            <span class="badge bg-secondary"><?php echo e($promotion->active ? 'Scheduled' : 'Inactive'); ?></span>
        <?php endif; ?>
    </h1>
    <div>
        <a href="<?php echo e(route('promotions.edit', $promotion)); ?>" class="btn btn-outline-primary">Edit</a>
        <a href="<?php echo e(route('promotions.index')); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value"><?php echo e(str_replace('_', ' ', ucfirst($promotion->type->value))); ?></div>
            <div class="kpi-label">Type</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($promotion->value, 2)); ?></div>
            <div class="kpi-label">Value</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($promotion->cap ?? 0, 2)); ?></div>
            <div class="kpi-label">Cap <?php if(!$promotion->cap): ?><small class="text-muted">(unlimited)</small><?php endif; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($promotion->claimed_liability ?? 0, 2)); ?></div>
            <div class="kpi-label">Claimed</div>
        </div>
    </div>
</div>

<!-- Liability Meter -->
<?php if($promotion->cap > 0): ?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <strong>Liability Meter</strong>
        <?php $pct = min(100, ($promotion->claimed_liability / $promotion->cap) * 100); ?>
        <div class="progress mt-2" style="height: 24px;">
            <div class="progress-bar bg-<?php echo e($pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success')); ?>"
                 role="progressbar" style="width: <?php echo e($pct); ?>%"
                 aria-valuenow="<?php echo e($pct); ?>" aria-valuemin="0" aria-valuemax="100">
                $<?php echo e(number_format($promotion->claimed_liability, 0)); ?> / $<?php echo e(number_format($promotion->cap, 0)); ?> (<?php echo e(number_format($pct, 1)); ?>%)
            </div>
        </div>
        <small class="text-muted mt-1 d-block">Remaining: $<?php echo e(number_format(max(0, $promotion->cap - $promotion->claimed_liability), 2)); ?></small>
    </div>
</div>
<?php endif; ?>

<!-- Promo Details -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white"><strong>📋 Details</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-2">Description</dt>
            <dd class="col-sm-10"><?php echo e($promotion->description ?? '—'); ?></dd>

            <dt class="col-sm-2">Period</dt>
            <dd class="col-sm-10">
                <?php echo e($promotion->starts_at->format('M d, Y g:i A')); ?>

                <?php if($promotion->ends_at): ?>
                    — <?php echo e($promotion->ends_at->format('M d, Y g:i A')); ?>

                <?php else: ?>
                    — No end date
                <?php endif; ?>
            </dd>

            <dt class="col-sm-2">Terms</dt>
            <dd class="col-sm-10"><?php echo e($promotion->terms ?? '—'); ?></dd>

            <dt class="col-sm-2">Created</dt>
            <dd class="col-sm-10"><?php echo e($promotion->created_at->format('M d, Y g:i A')); ?></dd>
        </dl>
    </div>
</div>

<!-- Redemptions Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>📋 Redemptions</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Ledger Entry</th>
                        <th>Claimed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promotion->redemptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $redemption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('players.show', $redemption->player)); ?>"><?php echo e($redemption->player->name); ?></a>
                        </td>
                        <td>$<?php echo e(number_format($redemption->amount, 2)); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($redemption->status); ?></span></td>
                        <td>
                            <?php if($redemption->ledgerEntry): ?>
                                <a href="<?php echo e(route('ledger.entries.show', $redemption->ledgerEntry)); ?>">
                                    <?php echo e($redemption->ledgerEntry->entry_number); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($redemption->claimed_at?->format('M d, Y g:i A') ?? $redemption->created_at->format('M d, Y g:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">No redemptions yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/promotions/show.blade.php ENDPATH**/ ?>
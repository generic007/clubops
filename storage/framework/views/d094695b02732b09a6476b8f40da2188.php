<?php $__env->startSection('title', 'Reconciliation - ' . $reconciliation->reconciliation_date->format('M d, Y')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        ✅ Reconciliation: <?php echo e($reconciliation->reconciliation_date->format('F d, Y')); ?>

    </h1>
    <div>
        <a href="<?php echo e(route('reconciliations.index')); ?>" class="btn btn-outline-secondary">Back</a>
        <?php if(!$reconciliation->isLocked() && !$reconciliation->hasVariance()): ?>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#lockModal">
            🔒 Lock & Close Day
        </button>
        <?php endif; ?>
    </div>
</div>

<?php if($reconciliation->hasVariance()): ?>
<div class="alert alert-danger">
    ⚠️ <strong>Variance detected:</strong> $<?php echo e(number_format($reconciliation->variance, 2)); ?>

    difference between platform ($<?php echo e(number_format($reconciliation->platform_total, 2)); ?>)
    and ledger ($<?php echo e(number_format($reconciliation->ledger_total, 2)); ?>).
</div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">$<?php echo e(number_format($reconciliation->platform_total, 2)); ?></div>
            <div class="kpi-label">Platform Total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">$<?php echo e(number_format($reconciliation->ledger_total, 2)); ?></div>
            <div class="kpi-label">Ledger Total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value <?php echo e($reconciliation->hasVariance() ? 'text-danger' : 'text-success'); ?>">
                $<?php echo e(number_format($reconciliation->variance, 2)); ?>

            </div>
            <div class="kpi-label">Variance</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value">
                <?php if($reconciliation->isLocked()): ?>
                    <span class="text-success">🔒 Locked</span>
                <?php else: ?>
                    <span class="text-warning">🔓 Open</span>
                <?php endif; ?>
            </div>
            <div class="kpi-label">Status</div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>📋 Reconciliation Items</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reconciliation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($item->entry): ?>
                                <a href="<?php echo e(route('ledger.entries.show', $item->entry)); ?>">
                                    <?php echo e($item->entry->entry_number); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo e(number_format($item->amount, 2)); ?></td>
                        <td><span class="badge bg-info"><?php echo e($item->type); ?></span></td>
                        <td><?php echo e($item->notes ?? '—'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center py-3 text-muted">No items</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if($reconciliation->isLocked()): ?>
<div class="alert alert-info mt-4">
    🔒 Locked by <?php echo e($reconciliation->locker?->name ?? 'System'); ?> on <?php echo e($reconciliation->locked_at->format('F d, Y g:i A')); ?>.
</div>
<?php endif; ?>

<!-- Lock Modal -->
<?php if(!$reconciliation->isLocked() && !$reconciliation->hasVariance()): ?>
<div class="modal fade" id="lockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('reconciliations.lock', $reconciliation)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">🔒 Lock & Close Day</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will lock all ledger entries for <strong><?php echo e($reconciliation->reconciliation_date->format('F d, Y')); ?></strong>.</p>
                    <p class="text-danger mb-0">⚠️ No further entries can be made for this date after locking.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Lock Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reconciliations/show.blade.php ENDPATH**/ ?>
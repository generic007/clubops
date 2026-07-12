<?php $__env->startSection('title', 'Reconciliations'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✅ Reconciliations</h1>
    <a href="<?php echo e(route('reconciliations.create')); ?>" class="btn btn-primary">+ New Reconciliation</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Platform Total</th>
                        <th>Ledger Total</th>
                        <th>Variance</th>
                        <th>Locked At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reconciliations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($recon->reconciliation_date->format('M d, Y')); ?></td>
                        <td>
                            <?php if($recon->isLocked()): ?>
                                <span class="badge bg-success">Locked</span>
                            <?php elseif($recon->hasVariance()): ?>
                                <span class="badge bg-warning">Variance</span>
                            <?php else: ?>
                                <span class="badge bg-info">Complete</span>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo e(number_format($recon->platform_total, 2)); ?></td>
                        <td>$<?php echo e(number_format($recon->ledger_total, 2)); ?></td>
                        <td class="<?php echo e($recon->hasVariance() ? 'text-danger fw-bold' : ''); ?>">
                            $<?php echo e(number_format($recon->variance, 2)); ?>

                        </td>
                        <td><?php echo e($recon->locked_at?->format('M d, Y g:i A') ?? '—'); ?></td>
                        <td>
                            <a href="<?php echo e(route('reconciliations.show', $recon)); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No reconciliations yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($reconciliations->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($reconciliations->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reconciliations/index.blade.php ENDPATH**/ ?>
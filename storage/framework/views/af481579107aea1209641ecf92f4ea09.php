<?php $__env->startSection('title', 'Promo Liability'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎁 Promo Liability Report</h1>
    <a href="<?php echo e(route('reports.promo-liability')); ?>?csv=1" class="btn btn-outline-success">📥 CSV</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Promotion</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Cap</th>
                        <th>Claimed</th>
                        <th>Remaining</th>
                        <th>Utilization</th>
                        <th>Period</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($promo->name); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $promo->type->value)); ?></span></td>
                        <td>$<?php echo e(number_format($promo->value, 2)); ?></td>
                        <td>
                            <?php if($promo->cap): ?>
                                $<?php echo e(number_format($promo->cap, 2)); ?>

                            <?php else: ?>
                                <span class="text-muted">Unlimited</span>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo e(number_format($promo->claimed_liability ?? 0, 2)); ?></td>
                        <td>
                            <?php if($promo->cap): ?>
                                $<?php echo e(number_format(max(0, $promo->cap - $promo->claimed_liability), 2)); ?>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($promo->cap > 0): ?>
                                <?php $pct = min(100, ($promo->claimed_liability / $promo->cap) * 100); ?>
                                <div class="progress" style="height: 16px;">
                                    <div class="progress-bar bg-<?php echo e($pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success')); ?>"
                                         style="width: <?php echo e($pct); ?>%">
                                        <?php echo e(number_format($pct, 1)); ?>%
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small>
                                <?php echo e($promo->starts_at->format('M d')); ?>

                                — <?php echo e($promo->ends_at?->format('M d, Y') ?? '∞'); ?>

                            </small>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No active promotions found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalCap, 2)); ?></div>
            <div class="kpi-label">Total Cap</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalClaimed, 2)); ?></div>
            <div class="kpi-label">Total Claimed</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalRemaining, 2)); ?></div>
            <div class="kpi-label">Total Remaining</div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/promo-liability.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Promotions'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎁 Promotions</h1>
    <div class="d-flex gap-2 align-items-center">
        @x('export-button', ['route' => route('promotions.export')])
        <a href="<?php echo e(route('promotions.create')); ?>" class="btn btn-primary">+ New Promotion</a>
    </div>
</div>

<!-- Search -->
<div class="d-flex gap-3 align-items-start mb-4 flex-wrap">
    @x('search-bar', ['route' => route('promotions.index'), 'placeholder' => 'Search promotions...'])
</div>

<!-- Loading Skeleton -->
<div id="loading-promotions" x-data x-init="$el.remove()">
    @x('skeleton-table', ['rows' => 5, 'cols' => 8])
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Total Liability</th>
                        <th>Claimed</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($promo->name); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $promo->type->value)); ?></span>
                        </td>
                        <td>$<?php echo e(number_format($promo->value, 2)); ?></td>
                        <td>
                            <?php if($promo->cap): ?>
                                $<?php echo e(number_format($promo->cap, 2)); ?>

                            <?php else: ?>
                                <span class="text-muted">Uncapped</span>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo e(number_format($promo->claimed_liability ?? 0, 2)); ?></td>
                        <td>
                            <?php echo e($promo->starts_at->format('M d')); ?>

                            <?php if($promo->ends_at): ?>
                                — <?php echo e($promo->ends_at->format('M d, Y')); ?>

                            <?php else: ?>
                                — ∞
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($promo->active && $promo->isActive()): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e($promo->active ? 'Scheduled' : 'Inactive'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('promotions.show', $promo)); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="<?php echo e(route('promotions.edit', $promo)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No promotions yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($promotions->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($promotions->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/promotions/index.blade.php ENDPATH**/ ?>
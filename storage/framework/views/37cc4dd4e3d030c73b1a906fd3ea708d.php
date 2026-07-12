<?php $__env->startSection('title', 'Activity by Platform'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Activity by Platform</h1>
</div>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($platform->total); ?></div>
            <div class="kpi-label"><?php echo e($platform->platform); ?></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4 text-muted">
                No platform accounts found.
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/activity-by-platform.blade.php ENDPATH**/ ?>
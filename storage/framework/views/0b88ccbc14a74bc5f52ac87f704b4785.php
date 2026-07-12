<?php $__env->startSection('title', 'Ledger Exceptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚠️ Ledger Exceptions</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $issues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="alert alert-danger mb-2">
                ⚠️ <?php echo e($issue); ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="alert alert-success mb-0">
                ✅ No ledger exceptions found. All entries are balanced.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/ledger-exceptions.blade.php ENDPATH**/ ?>
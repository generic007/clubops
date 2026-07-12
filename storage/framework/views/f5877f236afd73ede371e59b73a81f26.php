<?php $__env->startSection('content'); ?>
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="display-1 mb-3">🎉</div>
            <h1 class="fw-bold">You're All Set!</h1>
            <p class="fs-5 text-muted mt-3">
                Your subscription is active. Welcome to ClubOps.
            </p>
            <?php if($plan): ?>
                <p class="badge bg-success fs-6"><?php echo e($plan->name); ?> Plan</p>
            <?php endif; ?>
            <div class="mt-4">
                <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/billing/success.blade.php ENDPATH**/ ?>
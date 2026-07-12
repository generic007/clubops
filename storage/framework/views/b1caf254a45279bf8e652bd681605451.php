<?php $__env->startSection('content'); ?>
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Payment Cancelled</h1>
            <p class="fs-5 text-muted mt-3">
                No worries — you weren't charged. You can try again whenever you're ready.
            </p>
            <div class="mt-4">
                <a href="<?php echo e(route('billing.index')); ?>" class="btn btn-outline-primary">Back to Plans</a>
                <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-primary ms-2">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/billing/cancelled.blade.php ENDPATH**/ ?>
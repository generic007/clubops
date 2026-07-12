<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Choose Your Plan</h1>
        <p class="text-muted fs-5">Everything you need to run your poker club, in one place.</p>

        <?php if($onTrial): ?>
            <div class="alert alert-info d-inline-block mt-2">
                🎉 You're on a <strong>free trial</strong> until <?php echo e($club->trial_ends_at->format('M d, Y')); ?>.
            </div>
        <?php endif; ?>

        <?php if($isActive): ?>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <span class="badge bg-success fs-6">Active: <?php echo e($currentPlan->name ?? 'Current Plan'); ?></span>
                <?php if($club->stripe_id): ?>
                    <a href="<?php echo e(route('billing.portal')); ?>" class="btn btn-outline-secondary btn-sm">Manage Billing</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row justify-content-center g-4">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-5 col-lg-4">
            <div class="card h-100 shadow-sm <?php echo e($currentPlan && $currentPlan->id === $plan->id ? 'border-primary border-2' : ''); ?>">
                <?php if($currentPlan && $currentPlan->id === $plan->id): ?>
                    <div class="card-header bg-primary text-white text-center fw-semibold">
                        Current Plan
                    </div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title fw-bold"><?php echo e($plan->name); ?></h3>
                    <p class="text-muted small"><?php echo e($plan->description); ?></p>

                    <div class="my-3 text-center">
                        <span class="display-5 fw-bold"><?php echo e($plan->monthlyPrice()); ?></span>
                        <span class="text-muted">/month</span>
                        <?php if($plan->yearlyPrice()): ?>
                            <div class="small text-muted mt-1">
                                <?php echo e($plan->yearlyPrice()); ?>/year (<?php echo e($plan->monthlyPricePerMonth()); ?>/mo)
                            </div>
                        <?php endif; ?>
                    </div>

                    <ul class="list-unstyled flex-grow-1">
                        <?php $__currentLoopData = json_decode($plan->features ?? '[]', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="mb-2">
                            <span class="text-success me-2">✓</span> <?php echo e($feature); ?>

                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <?php if($isActive && $currentPlan && $currentPlan->id === $plan->id): ?>
                        <button class="btn btn-success w-100 mt-auto" disabled>Current Plan</button>
                    <?php elseif($isActive && $currentPlan && $currentPlan->tier > $plan->tier): ?>
                        <button class="btn btn-outline-secondary w-100 mt-auto" disabled>Downgrade via Billing Portal</button>
                    <?php else: ?>
                        <div class="d-grid gap-2 mt-auto">
                            <a href="<?php echo e(route('billing.checkout', ['plan' => $plan->slug, 'interval' => 'monthly'])); ?>"
                               class="btn btn-primary">
                                Subscribe Monthly
                            </a>
                            <?php if($plan->yearly_price_cents): ?>
                                <a href="<?php echo e(route('billing.checkout', ['plan' => $plan->slug, 'interval' => 'yearly'])); ?>"
                                   class="btn btn-outline-primary btn-sm">
                                    Subscribe Yearly — Save 17%
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="text-center mt-5 text-muted small">
        <p>All plans include a <strong>30-day free trial</strong> to evaluate ClubOps risk-free.</p>
        <p>Need something custom? <a href="mailto:hello@juncturelogic.com">Contact us</a>.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/billing/pricing.blade.php ENDPATH**/ ?>
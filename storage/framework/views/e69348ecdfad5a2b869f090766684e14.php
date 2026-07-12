<?php $__env->startSection('title', 'Open Disputes'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">⚖️ Open Disputes</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Player</th>
                        <th>Amount</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><a href="<?php echo e(route('ledger.entries.show', $entry)); ?>"><?php echo e($entry->entry_number); ?></a></td>
                        <td><?php echo e($entry->lines->first()?->player?->name ?? '—'); ?></td>
                        <td>$<?php echo e(number_format($entry->lines->sum('debit'), 2)); ?></td>
                        <td><?php echo e($entry->creator?->name ?? 'System'); ?></td>
                        <td><?php echo e($entry->entry_date->format('M d, Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No open disputes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($entries->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($entries->withQueryString()->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/open-disputes.blade.php ENDPATH**/ ?>
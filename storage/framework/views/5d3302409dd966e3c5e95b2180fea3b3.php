<?php $__env->startSection('title', 'Entry #' . $entry->entry_number); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Entry #<?php echo e($entry->entry_number); ?></h1>
        <div class="d-flex gap-2">
            <span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $entry->type)); ?></span>
            <span class="badge bg-<?php echo e($entry->locked ? 'secondary' : 'success'); ?>"><?php echo e($entry->locked ? 'Locked' : 'Open'); ?></span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('ledger.entries.index')); ?>" class="btn btn-outline-secondary">← Back</a>
        <?php if(!$entry->locked): ?>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#voidModal">↩ Void Entry</button>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Date</td><td><?php echo e($entry->entry_date->format('M d, Y')); ?></td></tr>
                    <tr><td class="text-muted">Created By</td><td><?php echo e($entry->creator?->name); ?></td></tr>
                    <tr><td class="text-muted">Reference</td><td><?php echo e($entry->reference ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Balance</td>
                        <td class="fw-bold <?php echo e($entry->isBalanced() ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e($entry->isBalanced() ? '✅ Balanced' : '❌ Unbalanced'); ?>

                        </td>
                    </tr>
                    <?php if($entry->reversedEntry): ?>
                    <tr><td class="text-muted">Reverses</td><td><a href="<?php echo e(route('ledger.entries.show', $entry->reversedEntry)); ?>">#<?php echo e($entry->reversedEntry->entry_number); ?></a></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>Journal Lines</strong></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Account</th><th>Player</th><th>Debit</th><th>Credit</th></tr>
                    </thead>
                    <tbody>
                        <?php $totalDebit = 0; $totalCredit = 0; ?>
                        <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($line->account?->code); ?> — <?php echo e($line->account?->name); ?></td>
                            <td><?php echo e($line->player?->name ?? '—'); ?></td>
                            <td>$<?php echo e(number_format($line->debit, 2)); ?></td>
                            <td>$<?php echo e(number_format($line->credit, 2)); ?></td>
                        </tr>
                        <?php $totalDebit += $line->debit; $totalCredit += $line->credit; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">Totals</td>
                            <td>$<?php echo e(number_format($totalDebit, 2)); ?></td>
                            <td>$<?php echo e(number_format($totalCredit, 2)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📝 Description</strong></div>
            <div class="card-body">
                <?php echo e($entry->description); ?>

            </div>
        </div>
    </div>
</div>

<!-- Void Modal -->
<?php if(!$entry->locked): ?>
<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('ledger.entries.void', $entry)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">↩ Void Entry #<?php echo e($entry->entry_number); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will create a reversal entry. The original entry will be locked.</p>
                    <div class="mb-3">
                        <label class="form-label">Reason for void *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Why is this being voided?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Void</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/ledger/entries/show.blade.php ENDPATH**/ ?>
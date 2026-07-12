<?php $__env->startSection('title', 'Ledger Entries'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 Ledger Entries</h1>
    <a href="<?php echo e(route('ledger.entries.create')); ?>" class="btn btn-primary">+ New Entry</a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo e(request('date', today()->format('Y-m-d'))); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->value); ?>" <?php echo e(request('type') === $t->value ? 'selected' : ''); ?>><?php echo e(str_replace('_', ' ', $t->value)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Entry #</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Date</th>
                    <th>Locked</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><a href="<?php echo e(route('ledger.entries.show', $entry)); ?>" class="fw-semibold"><?php echo e($entry->entry_number); ?></a></td>
                    <td><span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $entry->type)); ?></span></td>
                    <td><?php echo e(\Illuminate\Support\Str::limit($entry->description, 40)); ?></td>
                    <td>$<?php echo e(number_format($entry->lines->sum('debit'), 2)); ?></td>
                    <td>$<?php echo e(number_format($entry->lines->sum('credit'), 2)); ?></td>
                    <td><?php echo e($entry->entry_date->format('M d, Y')); ?></td>
                    <td><?php echo $entry->locked ? '🔒' : '🔓'; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center py-5 text-muted">No entries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($entries->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($entries->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/ledger/entries/index.blade.php ENDPATH**/ ?>
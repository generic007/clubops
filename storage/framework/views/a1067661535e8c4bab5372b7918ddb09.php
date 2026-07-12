<?php $__env->startSection('title', 'Daily Ledger'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 Daily Ledger: <?php echo e($date->format('F d, Y')); ?></h1>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('reports.daily-ledger', $date->copy()->subDay()->format('Y-m-d'))); ?>" class="btn btn-outline-secondary">← Previous</a>
        <a href="<?php echo e(route('reports.daily-ledger', $date->copy()->addDay()->format('Y-m-d'))); ?>" class="btn btn-outline-secondary">Next →</a>
        <a href="<?php echo e(route('reports.daily-ledger', $date->format('Y-m-d'))); ?>?csv=1" class="btn btn-outline-success">📥 CSV</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('reports.daily-ledger')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Select Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo e($date->format('Y-m-d')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">View</button>
            </div>
        </form>
    </div>
</div>

<!-- Totals -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value"><?php echo e($entries->count()); ?></div>
            <div class="kpi-label">Total Entries</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalDebit, 2)); ?></div>
            <div class="kpi-label">Total Debits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalCredit, 2)); ?></div>
            <div class="kpi-label">Total Credits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($totalCredit - $totalDebit, 2)); ?></div>
            <div class="kpi-label">Net</div>
        </div>
    </div>
</div>

<!-- Entries Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Locked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><a href="<?php echo e(route('ledger.entries.show', $entry)); ?>"><?php echo e($entry->entry_number); ?></a></td>
                        <td><span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $entry->type->value)); ?></span></td>
                        <td><?php echo e(\Illuminate\Support\Str::limit($entry->description, 50)); ?></td>
                        <td>$<?php echo e(number_format($entry->lines->sum('debit'), 2)); ?></td>
                        <td>$<?php echo e(number_format($entry->lines->sum('credit'), 2)); ?></td>
                        <td>
                            <?php if($entry->locked): ?>
                                <span class="badge bg-secondary">Locked</span>
                            <?php else: ?>
                                <span class="badge bg-success">Open</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-3 text-muted">No entries for this date</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/daily-ledger.blade.php ENDPATH**/ ?>
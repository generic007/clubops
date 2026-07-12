<?php $__env->startSection('title', 'Statement - ' . $player->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📋 Player Statement: <?php echo e($player->name); ?></h1>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('players.show', $player)); ?>" class="btn btn-outline-secondary">Back to Player</a>
        <a href="<?php echo e(route('reports.player-statement', $player)); ?>?csv=1&from=<?php echo e(request('from')); ?>&to=<?php echo e(request('to')); ?>"
           class="btn btn-outline-success">📥 CSV</a>
    </div>
</div>

<!-- Balance & Filters -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-value">$<?php echo e(number_format($balance, 2)); ?></div>
            <div class="kpi-label">Current Balance</div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('reports.player-statement', $player)); ?>" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" value="<?php echo e(request('from', now()->startOfMonth()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" value="<?php echo e(request('to', now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statement Entries Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>📋 Ledger Activity</strong>
        <small class="text-muted ms-2"><?php echo e($entries->total()); ?> entries</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Entry #</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $runningBalance = 0; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $runningBalance += ($line->credit - $line->debit);
                        ?>
                    <tr>
                        <td><?php echo e($line->entry?->entry_date->format('M d, Y') ?? '—'); ?></td>
                        <td>
                            <?php if($line->entry): ?>
                                <a href="<?php echo e(route('ledger.entries.show', $line->entry)); ?>"><?php echo e($line->entry->entry_number); ?></a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($line->entry?->description ?? '—'); ?></td>
                        <td>$<?php echo e(number_format($line->debit, 2)); ?></td>
                        <td>$<?php echo e(number_format($line->credit, 2)); ?></td>
                        <td class="fw-bold">$<?php echo e(number_format($runningBalance, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-3 text-muted">No activity in this period</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($entries->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($entries->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/player-statement.blade.php ENDPATH**/ ?>
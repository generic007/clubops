<?php $__env->startSection('title', 'Audit Log'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📜 Audit Log</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('audit-log')); ?>" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="from" class="form-control" value="<?php echo e(request('from', now()->startOfMonth()->format('Y-m-d'))); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="to" class="form-control" value="<?php echo e(request('to', now()->format('Y-m-d'))); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Agent</label>
                <select name="agent_id" class="form-select">
                    <option value="">All Agents</option>
                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>" <?php echo e(request('agent_id') == $a->id ? 'selected' : ''); ?>>
                            <?php echo e($a->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Action</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="player_created" <?php echo e(request('action') === 'player_created' ? 'selected' : ''); ?>>Player Created</option>
                    <option value="player_status_changed" <?php echo e(request('action') === 'player_status_changed' ? 'selected' : ''); ?>>Status Change</option>
                    <option value="player_note_created" <?php echo e(request('action') === 'player_note_created' ? 'selected' : ''); ?>>Note Added</option>
                    <option value="ledger_entry_created" <?php echo e(request('action') === 'ledger_entry_created' ? 'selected' : ''); ?>>Ledger Entry</option>
                    <option value="ledger_entry_voided" <?php echo e(request('action') === 'ledger_entry_voided' ? 'selected' : ''); ?>>Entry Voided</option>
                    <option value="reconciliation_created" <?php echo e(request('action') === 'reconciliation_created' ? 'selected' : ''); ?>>Reconciliation</option>
                    <option value="daily_close" <?php echo e(request('action') === 'daily_close' ? 'selected' : ''); ?>>Daily Close</option>
                    <option value="risk_flag_raised" <?php echo e(request('action') === 'risk_flag_raised' ? 'selected' : ''); ?>>Risk Flag</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Action</th>
                        <th>Target</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->created_at->format('M d, Y g:i A')); ?></td>
                        <td><?php echo e($log->agent?->name ?? 'System'); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $log->action)); ?></span>
                        </td>
                        <td>
                            <?php if($log->auditable_type): ?>
                                <small><?php echo e(class_basename($log->auditable_type)); ?> #<?php echo e($log->auditable_id); ?></small>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(\Illuminate\Support\Str::limit($log->description, 80) ?? '—'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No audit log entries found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($logs->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($logs->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/audit-log/index.blade.php ENDPATH**/ ?>
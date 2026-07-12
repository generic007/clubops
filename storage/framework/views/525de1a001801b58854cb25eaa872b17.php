<?php $__env->startSection('title', $import->filename); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 <?php echo e($import->filename); ?></h1>
    <div>
        <a href="<?php echo e(route('imports.index')); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($import->total_rows); ?></div>
            <div class="kpi-label">Total Rows</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-success"><?php echo e($import->accepted_rows); ?></div>
            <div class="kpi-label">Accepted</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-warning"><?php echo e($import->skipped_rows); ?></div>
            <div class="kpi-label">Skipped</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-danger"><?php echo e($import->flagged_rows); ?></div>
            <div class="kpi-label">Flagged</div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>📋 Rows</strong>
        <span class="badge bg-secondary"><?php echo e($import->type); ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Row #</th>
                        <th>Raw Data</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $import->rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($row->row_number); ?></td>
                        <td>
                            <code class="small"><?php echo e(json_encode($row->raw_data)); ?></code>
                        </td>
                        <td>
                            <?php if($row->status === 'accepted'): ?>
                                <span class="badge bg-success">Accepted</span>
                            <?php elseif($row->status === 'skipped'): ?>
                                <span class="badge bg-warning">Skipped</span>
                            <?php elseif($row->status === 'flagged'): ?>
                                <span class="badge bg-danger">Flagged</span>
                            <?php else: ?>
                                <span class="badge bg-info">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($row->notes ?? '—'); ?></td>
                        <td>
                            <?php if($import->status !== 'completed' && $row->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('imports.accept', [$import, $row])); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('imports.skip', [$import, $row])); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-warning">Skip</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">No rows</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/imports/show.blade.php ENDPATH**/ ?>
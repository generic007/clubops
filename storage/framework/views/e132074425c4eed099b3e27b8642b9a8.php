<?php $__env->startSection('title', 'Imports'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 Imports</h1>
    <a href="<?php echo e(route('imports.create')); ?>" class="btn btn-primary">+ New Import</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Filename</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Accepted</th>
                        <th>Skipped</th>
                        <th>Flagged</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $imports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $import): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?php echo e($import->type); ?></span></td>
                        <td><?php echo e($import->filename); ?></td>
                        <td>
                            <?php if($import->status === 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php elseif($import->status === 'processing'): ?>
                                <span class="badge bg-warning">Processing</span>
                            <?php elseif($import->status === 'flagged'): ?>
                                <span class="badge bg-danger">Flagged</span>
                            <?php else: ?>
                                <span class="badge bg-info"><?php echo e(ucfirst($import->status)); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($import->total_rows); ?></td>
                        <td><?php echo e($import->accepted_rows); ?></td>
                        <td><?php echo e($import->skipped_rows); ?></td>
                        <td><?php echo e($import->flagged_rows); ?></td>
                        <td><?php echo e($import->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('imports.show', $import)); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No imports yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($imports->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($imports->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/imports/index.blade.php ENDPATH**/ ?>
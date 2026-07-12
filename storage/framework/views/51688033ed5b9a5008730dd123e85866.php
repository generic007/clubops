<?php $__env->startSection('title', 'Compliance'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🔒 Compliance Overview</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('compliance.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Player name or email..."
                       value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Compliance Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="complete" <?php echo e(request('status') === 'complete' ? 'selected' : ''); ?>>Complete</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="excluded" <?php echo e(request('status') === 'excluded' ? 'selected' : ''); ?>>Excluded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ID Verification</label>
                <select name="id_status" class="form-select">
                    <option value="">All</option>
                    <option value="verified" <?php echo e(request('id_status') === 'verified' ? 'selected' : ''); ?>>Verified</option>
                    <option value="unverified" <?php echo e(request('id_status') === 'unverified' ? 'selected' : ''); ?>>Unverified</option>
                    <option value="pending" <?php echo e(request('id_status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-success"><?php echo e($completeCount); ?></div>
            <div class="kpi-label">Compliance Complete</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-warning"><?php echo e($pendingCount); ?></div>
            <div class="kpi-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value text-danger"><?php echo e($excludedCount); ?></div>
            <div class="kpi-label">Excluded</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card text-center">
            <div class="kpi-value"><?php echo e($idVerifiedCount); ?></div>
            <div class="kpi-label">ID Verified</div>
        </div>
    </div>
</div>

<!-- Players Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Status</th>
                        <th>Compliance</th>
                        <th>ID Verified</th>
                        <th>Location</th>
                        <th>DOB</th>
                        <th>Excluded</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="<?php echo e($player->isExcluded() ? 'table-danger' : ''); ?>">
                        <td>
                            <a href="<?php echo e(route('compliance.show', $player)); ?>" class="fw-semibold"><?php echo e($player->name); ?></a>
                        </td>
                        <td><span class="badge-status badge-<?php echo e($player->status->value); ?>"><?php echo e(ucfirst($player->status->value)); ?></span></td>
                        <td>
                            <?php if($player->compliance_complete): ?>
                                <span class="badge bg-success">Complete</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($player->compliance && $player->compliance->id_verification_status === 'verified'): ?>
                                <span class="badge bg-success">Verified</span>
                            <?php elseif($player->compliance && $player->compliance->id_verification_status === 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Unverified</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($player->compliance?->location ?? '—'); ?></td>
                        <td><?php echo e($player->compliance?->date_of_birth?->format('M d, Y') ?? '—'); ?></td>
                        <td>
                            <?php if($player->isExcluded()): ?>
                                <span class="badge bg-danger">⚠ Excluded</span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('compliance.show', $player)); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No players found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($players->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($players->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/compliance/index.blade.php ENDPATH**/ ?>
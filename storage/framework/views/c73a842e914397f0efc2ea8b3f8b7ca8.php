<?php $__env->startSection('title', 'Compliance - ' . $player->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🔒 Compliance Profile: <?php echo e($player->name); ?>

        <?php if($player->isExcluded()): ?>
            <span class="badge bg-danger">⚠ Excluded</span>
        <?php endif; ?>
    </h1>
    <div>
        <?php if($player->isExcluded()): ?>
            <form method="POST" action="<?php echo e(route('compliance.reinstate', $player)); ?>" class="d-inline"
                  onsubmit="return confirm('Reinstate this player?')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success">Reinstate Player</button>
            </form>
        <?php else: ?>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#excludeModal">
                Exclude Player
            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('compliance.index')); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📋 Player Info</strong></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8"><?php echo e($player->name); ?></dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge-status badge-<?php echo e($player->status->value); ?>"><?php echo e(ucfirst($player->status->value)); ?></span>
                    </dd>

                    <dt class="col-sm-4">Compliance</dt>
                    <dd class="col-sm-8">
                        <?php if($player->compliance_complete): ?>
                            <span class="badge bg-success">Complete</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4">Agent</dt>
                    <dd class="col-sm-8"><?php echo e($player->agent?->name ?? '—'); ?></dd>

                    <dt class="col-sm-4">Created</dt>
                    <dd class="col-sm-8"><?php echo e($player->created_at->format('M d, Y')); ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🪪 ID Verification</strong></div>
            <div class="card-body">
                <?php if($profile): ?>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Date of Birth</dt>
                        <dd class="col-sm-8"><?php echo e($profile->date_of_birth?->format('M d, Y') ?? '—'); ?></dd>

                        <dt class="col-sm-4">Location</dt>
                        <dd class="col-sm-8"><?php echo e($profile->location ?? '—'); ?></dd>

                        <dt class="col-sm-4">ID Status</dt>
                        <dd class="col-sm-8">
                            <?php if($profile->id_verification_status === 'verified'): ?>
                                <span class="badge bg-success">Verified</span>
                            <?php elseif($profile->id_verification_status === 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($profile->id_verification_status ?? 'unverified')); ?></span>
                            <?php endif; ?>
                        </dd>

                        <?php if($profile->id_verified_at): ?>
                        <dt class="col-sm-4">Verified At</dt>
                        <dd class="col-sm-8"><?php echo e($profile->id_verified_at->format('M d, Y g:i A')); ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-4">Notes</dt>
                        <dd class="col-sm-8"><?php echo e($profile->compliance_notes ?? '—'); ?></dd>
                    </dl>
                <?php else: ?>
                    <p class="text-muted mb-0">No compliance profile created yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Exclusion Timeline -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white"><strong>🚫 Exclusion Timeline</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Starts At</th>
                        <th>Ends At</th>
                        <th>Reason</th>
                        <th>Created By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $player->exclusions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exclusion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(ucfirst($exclusion->type)); ?></td>
                        <td><?php echo e($exclusion->starts_at->format('M d, Y')); ?></td>
                        <td><?php echo e($exclusion->ends_at?->format('M d, Y') ?? 'Indefinite'); ?></td>
                        <td><?php echo e($exclusion->reason ?? '—'); ?></td>
                        <td><?php echo e($exclusion->createdBy?->name ?? 'System'); ?></td>
                        <td>
                            <?php if($exclusion->isActive()): ?>
                                <span class="badge bg-danger">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Expired</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-3 text-muted">No exclusions</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Exclude Modal -->
<?php if(!$player->isExcluded()): ?>
<div class="modal fade" id="excludeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('compliance.exclude', $player)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">🚫 Exclude Player: <?php echo e($player->name); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will mark the player as excluded and prevent platform access.</p>
                    <div class="mb-3">
                        <label class="form-label">Exclusion Type</label>
                        <select name="type" class="form-select">
                            <option value="temporary">Temporary</option>
                            <option value="permanent">Permanent</option>
                            <option value="self_excluded">Self-Excluded</option>
                            <option value="regulatory">Regulatory</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ends At (leave blank for indefinite)</label>
                        <input type="date" name="ends_at" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Exclude Player</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/compliance/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Commissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4" style="max-width: 960px;">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">💵 Commission &amp; Rakeback</h1>
            <p class="text-muted mb-0"><?php echo e($club->name); ?></p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Structure</button>
    </div>

    <!-- Current Structures -->
    <div class="card">
        <div class="card-header"><strong>Commission Structures</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Type</th>
                            <th>Rate</th>
                            <th>Balance</th>
                            <th>Last Settled</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $structures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($s->agent->name); ?></td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?php echo e(str_replace('_', ' ', $s->type)); ?>

                                </span>
                            </td>
                            <td><?php echo e(($s->rate * 100)); ?>%</td>
                            <td class="amount">$<?php echo e(number_format($s->agent->commission_balance, 2)); ?></td>
                            <td class="text-muted"><?php echo e($s->agent->last_settled_at?->diffForHumans() ?? 'Never'); ?></td>
                            <td>
                                <?php if($s->active): ?>
                                    <span class="text-success">● Active</span>
                                <?php else: ?>
                                    <span class="text-danger">● Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?php if($s->agent->commission_balance > 0): ?>
                                    <form method="POST" action="<?php echo e(route('commissions.settle', $s->agent)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-outline-success" title="Settle">💰 Settle</button>
                                    </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('commissions.destroy', $s)); ?>" class="d-inline"
                                          onsubmit="return confirm('Remove this commission structure?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger">✕</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No commission structures yet. Add one to start tracking rakeback.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('commissions.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Commission Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" class="form-select" required>
                            <option value="">Select agent…</option>
                            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($a->id); ?>"><?php echo e($a->name); ?> — <?php echo e(ucfirst($a->role->value)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="rakeback_percentage">Rakeback (% of player rake)</option>
                            <option value="flat_fee_per_player">Flat fee per player</option>
                            <option value="volume_tiered">Volume-tiers</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rate (%)</label>
                        <input type="number" name="rate" class="form-control" step="0.1" min="0" max="100"
                               placeholder="e.g. 25 for 25%" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/agents/commissions.blade.php ENDPATH**/ ?>
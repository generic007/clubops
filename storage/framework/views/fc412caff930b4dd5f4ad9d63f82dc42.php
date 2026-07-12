<?php $__env->startSection('title', 'Agents'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🤝 Agents</h1>
    <div class="d-flex gap-2 align-items-center">
        @x('export-button', ['route' => route('agents.export')])
        <a href="<?php echo e(route('agents.create')); ?>" class="btn btn-primary">+ New Agent</a>
    </div>
</div>

<!-- Search -->
<div class="d-flex gap-3 align-items-start mb-4 flex-wrap">
    @x('search-bar', ['route' => route('agents.index'), 'placeholder' => 'Search agents...'])
</div>

<!-- Loading Skeleton -->
<div id="loading-agents" x-data x-init="$el.remove()">
    @x('skeleton-table', ['rows' => 5, 'cols' => 7])
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Players</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($agent->name); ?></td>
                        <td><?php echo e($agent->email); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($agent->role->value === 'owner' ? 'danger' : ($agent->role->value === 'manager' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst($agent->role->value)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($agent->active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($agent->players_count ?? $agent->players->count()); ?></td>
                        <td><?php echo e($agent->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('agents.edit', $agent)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No agents found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($agents->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($agents->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/agents/index.blade.php ENDPATH**/ ?>
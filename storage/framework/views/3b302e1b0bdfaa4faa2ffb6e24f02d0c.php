<?php $__env->startSection('title', 'Players'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">👥 Players</h1>
    <div class="d-flex gap-2 align-items-center">
        <a href="<?php echo e(route('players.export')); ?>" class="btn btn-outline-secondary btn-sm">📥 Export</a>
        <a href="<?php echo e(route('players.create')); ?>" class="btn btn-primary">+ Add Player</a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->value); ?>" <?php echo e(request('status') === $s->value ? 'selected' : ''); ?>><?php echo e(ucfirst($s->value)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="tag" class="form-select">
                    <option value="">All Tags</option>
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tag->name); ?>" <?php echo e(request('tag') === $tag->name ? 'selected' : ''); ?>><?php echo e($tag->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Tag Modal -->
<div class="modal fade" id="quickTagModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="quickTagForm">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">🏷️ Add Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag Name</label>
                        <input type="text" name="tag" class="form-control" placeholder="Enter tag name..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openTagForm(playerId) {
    const form = document.getElementById('quickTagForm');
    form.action = '/players/' + playerId + '/tags';
    const modal = new bootstrap.Modal(document.getElementById('quickTagModal'));
    modal.show();
}
</script>

<!-- Players Table -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Platform</th>
                    <th>Agent</th>
                    <th>Tags</th>
                    <th>Last Played</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <a href="<?php echo e(route('players.show', $player)); ?>" class="fw-semibold"><?php echo e($player->preferred_name ?? $player->name); ?></a>
                        <small class="d-block text-muted"><?php echo e($player->name !== $player->preferred_name ? $player->name : ''); ?></small>
                    </td>
                    <td><span class="badge-status badge-<?php echo e($player->status->value); ?>"><?php echo e(ucfirst($player->status->value)); ?></span></td>
                    <td>
                        <?php $__currentLoopData = $player->platformAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <small class="d-block"><?php echo e($acct->platform); ?>: <?php echo e($acct->username); ?></small>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo e($player->agent?->name ?? '—'); ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                            <?php $__currentLoopData = $player->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-secondary"><?php echo e($tag->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <button class="btn btn-sm btn-outline-secondary border-0 px-1 py-0" onclick="openTagForm(<?php echo e($player->id); ?>)" title="Add tag">➕</button>
                        </div>
                    </td>
                    <td><?php echo e($player->last_played_at ? $player->last_played_at->diffForHumans() : '—'); ?></td>
                    <td class="text-end">
                        <a href="<?php echo e(route('players.show', $player)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <span class="empty-icon">👥</span>
                            <div class="empty-title">No players yet</div>
                            <div class="empty-text">Add your first player to start tracking sessions, buy-ins, and cash-outs.</div>
                            <a href="<?php echo e(route('players.create')); ?>" class="btn btn-primary empty-action">+ Add Player</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($players->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($players->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/players/index.blade.php ENDPATH**/ ?>
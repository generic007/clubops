<?php $__env->startSection('title', 'Games'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Games</h1>
    <div class="d-flex gap-2 align-items-center">
        @x('export-button', ['route' => route('games.export')])
        <a href="<?php echo e(route('games.create')); ?>" class="btn btn-primary">+ New Game</a>
    </div>
</div>

<!-- Search + Filters -->
<div class="d-flex gap-3 align-items-start mb-4 flex-wrap">
    @x('search-bar', ['route' => route('games.index'), 'placeholder' => 'Search games...'])
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(request('type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Platform</label>
                <select name="platform" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p); ?>" <?php echo e(request('platform') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="<?php echo e(request('date')); ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<!-- Loading Skeleton -->
<div id="loading-games" x-data x-init="$el.remove()">
    @x('skeleton-table', ['rows' => 5, 'cols' => 7])
</div>

<!-- Games Table -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Type / Stakes</th>
                    <th>Platform</th>
                    <th>Scheduled</th>
                    <th>Status</th>
                    <th>Players</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><a href="<?php echo e(route('games.show', $game)); ?>" class="fw-semibold"><?php echo e($game->name); ?></a></td>
                    <td><?php echo e($game->type); ?> / <?php echo e($game->stakes); ?></td>
                    <td><span class="badge bg-secondary"><?php echo e($game->platform); ?></span></td>
                    <td><?php echo e($game->scheduled_at->format('M d, g:i A')); ?></td>
                    <td>
                        <?php
                            $statusColors = ['scheduled' => 'info', 'running' => 'success', 'completed' => 'secondary', 'cancelled' => 'danger'];
                        ?>
                        <span class="badge bg-<?php echo e($statusColors[$game->status] ?? 'secondary'); ?>">
                            <?php echo e(ucfirst($game->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($game->sessions_count ?? $game->sessions()->count()); ?></td>
                    <td>
                        <a href="<?php echo e(route('games.edit', $game)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <span class="empty-icon">🎮</span>
                            <div class="empty-title">No games scheduled</div>
                            <div class="empty-text">Schedule your first game to start tracking sessions and player activity.</div>
                            <a href="<?php echo e(route('games.create')); ?>" class="btn btn-primary empty-action">Schedule Game</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($games->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($games->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/games/index.blade.php ENDPATH**/ ?>
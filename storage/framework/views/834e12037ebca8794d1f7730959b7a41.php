<?php $__env->startSection('title', 'Edit Game'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">✏️ Edit Game</h1>
    <a href="<?php echo e(route('games.show', $game)); ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('games.update', $game)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Game Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $game->name)); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="PLO" <?php echo e($game->type === 'PLO' ? 'selected' : ''); ?>>PLO</option>
                        <option value="NLH" <?php echo e($game->type === 'NLH' ? 'selected' : ''); ?>>NLH</option>
                        <option value="Mixed" <?php echo e($game->type === 'Mixed' ? 'selected' : ''); ?>>Mixed</option>
                        <option value="PLO8" <?php echo e($game->type === 'PLO8' ? 'selected' : ''); ?>>PLO8</option>
                        <option value="Big O" <?php echo e($game->type === 'Big O' ? 'selected' : ''); ?>>Big O</option>
                        <option value="Tournament" <?php echo e($game->type === 'Tournament' ? 'selected' : ''); ?>>Tournament</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stakes</label>
                    <select name="stakes" class="form-select" required>
                        <option value="1/2" <?php echo e($game->stakes === '1/2' ? 'selected' : ''); ?>>$1/$2</option>
                        <option value="2/5" <?php echo e($game->stakes === '2/5' ? 'selected' : ''); ?>>$2/$5</option>
                        <option value="5/5" <?php echo e($game->stakes === '5/5' ? 'selected' : ''); ?>>$5/$5</option>
                        <option value="5/5/10" <?php echo e($game->stakes === '5/5/10' ? 'selected' : ''); ?>>$5/$5/$10</option>
                        <option value="5/10" <?php echo e($game->stakes === '5/10' ? 'selected' : ''); ?>>$5/$10</option>
                        <option value="10/20" <?php echo e($game->stakes === '10/20' ? 'selected' : ''); ?>>$10/$20</option>
                        <option value="25/50" <?php echo e($game->stakes === '25/50' ? 'selected' : ''); ?>>$25/$50</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select" required>
                        <option value="ClubGG" <?php echo e($game->platform === 'ClubGG' ? 'selected' : ''); ?>>ClubGG</option>
                        <option value="PPPoker" <?php echo e($game->platform === 'PPPoker' ? 'selected' : ''); ?>>PPPoker</option>
                        <option value="PokerBros" <?php echo e($game->platform === 'PokerBros' ? 'selected' : ''); ?>>PokerBros</option>
                        <option value="Home Game" <?php echo e($game->platform === 'Home Game' ? 'selected' : ''); ?>>Home Game</option>
                        <option value="Other" <?php echo e($game->platform === 'Other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" <?php echo e($game->status === 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                        <option value="running" <?php echo e($game->status === 'running' ? 'selected' : ''); ?>>Running</option>
                        <option value="completed" <?php echo e($game->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e($game->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scheduled Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control"
                           value="<?php echo e(old('scheduled_at', $game->scheduled_at->format('Y-m-d\TH:i'))); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Started At (optional)</label>
                    <input type="datetime-local" name="started_at" class="form-control"
                           value="<?php echo e(old('started_at', $game->started_at?->format('Y-m-d\TH:i'))); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ended At (optional)</label>
                    <input type="datetime-local" name="ended_at" class="form-control"
                           value="<?php echo e(old('ended_at', $game->ended_at?->format('Y-m-d\TH:i'))); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $game->notes)); ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Game</button>
                <a href="<?php echo e(route('games.show', $game)); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/games/edit.blade.php ENDPATH**/ ?>
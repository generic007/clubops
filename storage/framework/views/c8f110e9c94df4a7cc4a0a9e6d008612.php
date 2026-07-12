<?php $__env->startSection('title', 'New Game'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎮 Schedule Game</h1>
    <a href="<?php echo e(route('games.index')); ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('games.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Game Name</label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('name')); ?>" placeholder="e.g. Friday Night PLO" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Game Type</label>
                    <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Select...</option>
                        <option value="PLO" <?php echo e(old('type') === 'PLO' ? 'selected' : ''); ?>>PLO</option>
                        <option value="NLH" <?php echo e(old('type') === 'NLH' ? 'selected' : ''); ?>>NLH</option>
                        <option value="Mixed" <?php echo e(old('type') === 'Mixed' ? 'selected' : ''); ?>>Mixed Game</option>
                        <option value="PLO8" <?php echo e(old('type') === 'PLO8' ? 'selected' : ''); ?>>PLO8</option>
                        <option value="Big O" <?php echo e(old('type') === 'Big O' ? 'selected' : ''); ?>>Big O</option>
                        <option value="Tournament" <?php echo e(old('type') === 'Tournament' ? 'selected' : ''); ?>>Tournament</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stakes</label>
                    <select name="stakes" class="form-select <?php $__errorArgs = ['stakes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Select...</option>
                        <option value="1/2" <?php echo e(old('stakes') === '1/2' ? 'selected' : ''); ?>>$1/$2</option>
                        <option value="2/5" <?php echo e(old('stakes') === '2/5' ? 'selected' : ''); ?>>$2/$5</option>
                        <option value="5/5" <?php echo e(old('stakes') === '5/5' ? 'selected' : ''); ?>>$5/$5</option>
                        <option value="5/5/10" <?php echo e(old('stakes') === '5/5/10' ? 'selected' : ''); ?>>$5/$5/$10</option>
                        <option value="5/10" <?php echo e(old('stakes') === '5/10' ? 'selected' : ''); ?>>$5/$10</option>
                        <option value="10/20" <?php echo e(old('stakes') === '10/20' ? 'selected' : ''); ?>>$10/$20</option>
                        <option value="25/50" <?php echo e(old('stakes') === '25/50' ? 'selected' : ''); ?>>$25/$50</option>
                    </select>
                    <?php $__errorArgs = ['stakes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select <?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Select...</option>
                        <option value="ClubGG" <?php echo e(old('platform') === 'ClubGG' ? 'selected' : ''); ?>>ClubGG</option>
                        <option value="PPPoker" <?php echo e(old('platform') === 'PPPoker' ? 'selected' : ''); ?>>PPPoker</option>
                        <option value="PokerBros" <?php echo e(old('platform') === 'PokerBros' ? 'selected' : ''); ?>>PokerBros</option>
                        <option value="Home Game" <?php echo e(old('platform') === 'Home Game' ? 'selected' : ''); ?>>Home Game</option>
                        <option value="Other" <?php echo e(old('platform') === 'Other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Scheduled Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('scheduled_at')); ?>" required>
                    <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"
                              placeholder="Game format, special rules, player invites..."><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Schedule Game</button>
                <a href="<?php echo e(route('games.index')); ?>" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/games/create.blade.php ENDPATH**/ ?>
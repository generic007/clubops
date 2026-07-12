<?php $__env->startSection('title', $player->exists ? 'Edit Player' : 'Add Player'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo e($player->exists ? '✏️ Edit Player' : '👤 Add Player'); ?></h1>
    <a href="<?php echo e(route('players.index')); ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e($player->exists ? route('players.update', $player) : route('players.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($player->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $player->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Preferred Name</label>
                    <input type="text" name="preferred_name" class="form-control" value="<?php echo e(old('preferred_name', $player->preferred_name)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $player->email)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $player->phone)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__currentLoopData = App\Enums\PlayerStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->value); ?>" <?php echo e(old('status', $player->status?->value ?? 'lead') === $s->value ? 'selected' : ''); ?>><?php echo e(ucfirst($s->value)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referral Source</label>
                    <input type="text" name="referral_source" class="form-control" value="<?php echo e(old('referral_source', $player->referral_source)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agent</label>
                    <select name="agent_id" class="form-select">
                        <option value="">None</option>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agent->id); ?>" <?php echo e(old('agent_id', $player->agent_id) == $agent->id ? 'selected' : ''); ?>><?php echo e($agent->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned Admin</label>
                    <select name="assigned_admin_id" class="form-select">
                        <option value="">None</option>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agent->id); ?>" <?php echo e(old('assigned_admin_id', $player->assigned_admin_id) == $agent->id ? 'selected' : ''); ?>><?php echo e($agent->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Tags</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="<?php echo e($tag->id); ?>"
                                    <?php echo e(in_array($tag->id, old('tags', $player->tags->pluck('id')->toArray())) ? 'checked' : ''); ?>>
                                <label class="form-check-label"><?php echo e($tag->name); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preferred Game</label>
                    <select name="preferred_game" class="form-select">
                        <option value="">—</option>
                        <option value="PLO" <?php echo e(old('preferred_game', $player->preferred_game) === 'PLO' ? 'selected' : ''); ?>>PLO</option>
                        <option value="NLH" <?php echo e(old('preferred_game', $player->preferred_game) === 'NLH' ? 'selected' : ''); ?>>NLH</option>
                        <option value="Mixed" <?php echo e(old('preferred_game', $player->preferred_game) === 'Mixed' ? 'selected' : ''); ?>>Mixed</option>
                        <option value="PLO8" <?php echo e(old('preferred_game', $player->preferred_game) === 'PLO8' ? 'selected' : ''); ?>>PLO8</option>
                        <option value="Big O" <?php echo e(old('preferred_game', $player->preferred_game) === 'Big O' ? 'selected' : ''); ?>>Big O</option>
                        <option value="Tournament" <?php echo e(old('preferred_game', $player->preferred_game) === 'Tournament' ? 'selected' : ''); ?>>Tournament</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preferred Stakes</label>
                    <select name="preferred_stakes" class="form-select">
                        <option value="">—</option>
                        <option value="1/2" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '1/2' ? 'selected' : ''); ?>>$1/$2</option>
                        <option value="2/5" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '2/5' ? 'selected' : ''); ?>>$2/$5</option>
                        <option value="5/5" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '5/5' ? 'selected' : ''); ?>>$5/$5</option>
                        <option value="5/5/10" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '5/5/10' ? 'selected' : ''); ?>>$5/$5/$10</option>
                        <option value="5/10" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '5/10' ? 'selected' : ''); ?>>$5/$10</option>
                        <option value="10/20" <?php echo e(old('preferred_stakes', $player->preferred_stakes) === '10/20' ? 'selected' : ''); ?>>$10/$20</option>
                    </select>
                </div>
                    <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $player->notes)); ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <h5>🎮 Platform Accounts</h5>
                <div id="platform-accounts">
                    <?php $__currentLoopData = old('platform_accounts', $player->platformAccounts->toArray() ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $acct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row g-2 mb-2 platform-row">
                        <div class="col-4">
                            <select name="platform_accounts[<?php echo e($i); ?>][platform]" class="form-select">
                                <option value="ClubGG" <?php echo e(($acct['platform'] ?? '') === 'ClubGG' ? 'selected' : ''); ?>>ClubGG</option>
                                <option value="PPPoker" <?php echo e(($acct['platform'] ?? '') === 'PPPoker' ? 'selected' : ''); ?>>PPPoker</option>
                                <option value="PokerBros" <?php echo e(($acct['platform'] ?? '') === 'PokerBros' ? 'selected' : ''); ?>>PokerBros</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="text" name="platform_accounts[<?php echo e($i); ?>][username]" class="form-control" placeholder="Username" value="<?php echo e($acct['username'] ?? ''); ?>">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.platform-row').remove()">✕</button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPlatformRow()">+ Add Platform</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <?php echo e($player->exists ? 'Save Changes' : 'Create Player'); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let platformIndex = <?php echo e(old('platform_accounts') ? count(old('platform_accounts')) : ($player->platformAccounts->count() ?: 0)); ?>;
function addPlatformRow() {
    const container = document.getElementById('platform-accounts');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 platform-row';
    row.innerHTML = `
        <div class="col-4">
            <select name="platform_accounts[${platformIndex}][platform]" class="form-select">
                <option value="ClubGG">ClubGG</option>
                <option value="PPPoker">PPPoker</option>
                <option value="PokerBros">PokerBros</option>
            </select>
        </div>
        <div class="col-6">
            <input type="text" name="platform_accounts[${platformIndex}][username]" class="form-control" placeholder="Username">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.platform-row').remove()">✕</button>
        </div>`;
    container.appendChild(row);
    platformIndex++;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/players/edit.blade.php ENDPATH**/ ?>
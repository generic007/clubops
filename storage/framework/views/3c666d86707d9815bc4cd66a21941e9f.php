<?php $__env->startSection('title', 'New Import'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📥 New Import</h1>
    <a href="<?php echo e(route('imports.index')); ?>" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('imports.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Import Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Select Type</option>
                        <option value="players" <?php echo e(old('type') === 'players' ? 'selected' : ''); ?>>Players</option>
                        <option value="ledger" <?php echo e(old('type') === 'ledger' ? 'selected' : ''); ?>>Ledger Entries</option>
                        <option value="game_sessions" <?php echo e(old('type') === 'game_sessions' ? 'selected' : ''); ?>>Game Sessions</option>
                        <option value="promotions" <?php echo e(old('type') === 'promotions' ? 'selected' : ''); ?>>Promotions</option>
                        <option value="tickets" <?php echo e(old('type') === 'tickets' ? 'selected' : ''); ?>>Tickets</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">CSV File <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           accept=".csv,.tsv,.txt" required>
                    <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <strong>Expected columns per type:</strong>
                <ul class="mb-0 small">
                    <li><strong>Players:</strong> name, email, phone, status, agent_email, platform, username</li>
                    <li><strong>Ledger:</strong> entry_date, type, description, account_code, debit, credit, player_email</li>
                    <li><strong>Game Sessions:</strong> player_email, platform, game_name, buy_in, cash_out, session_date</li>
                </ul>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Upload & Preview</button>
                <a href="<?php echo e(route('imports.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/imports/create.blade.php ENDPATH**/ ?>
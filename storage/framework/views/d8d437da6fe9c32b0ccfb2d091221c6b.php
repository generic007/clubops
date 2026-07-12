<?php $__env->startSection('title', 'Create Ledger Entry'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 New Ledger Entry</h1>
    <a href="<?php echo e(route('ledger.entries.index')); ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('ledger.entries.store')); ?>" id="ledger-form">
            <?php echo csrf_field(); ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Transaction Type *</label>
                    <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->value); ?>" <?php echo e(old('type') === $t->value ? 'selected' : ''); ?>><?php echo e(str_replace('_', ' ', $t->value)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date *</label>
                    <input type="date" name="entry_date" class="form-control <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('entry_date', today()->format('Y-m-d'))); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference (optional)</label>
                    <input type="text" name="reference" class="form-control" value="<?php echo e(old('reference')); ?>" placeholder="External ref #">
                </div>
                <div class="col-12">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" required><?php echo e(old('description')); ?></textarea>
                </div>
            </div>

            <h5 class="mb-3">Journal Lines</h5>
            <p class="text-muted small">Each line is a debit OR credit. Total debits must equal total credits.</p>

            <div id="ledger-lines">
                <div class="row g-2 mb-2 line-row">
                    <div class="col-md-3">
                        <select name="lines[0][account_id]" class="form-select" required>
                            <option value="">Select Account</option>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acct->id); ?>"><?php echo e($acct->code); ?> — <?php echo e($acct->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="lines[0][player_id]" class="form-select">
                            <option value="">All/General</option>
                            <?php $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lines[0][debit]" class="form-control debit-input" placeholder="Debit $" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lines[0][credit]" class="form-control credit-input" placeholder="Credit $" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.line-row').remove(); updateBalance();">✕</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addLineRow()">+ Add Line</button>

            <div id="balance-display" class="alert alert-info mb-4">
                <strong>Balance Check:</strong> Debits: <span id="total-debit">$0.00</span> | Credits: <span id="total-credit">$0.00</span> |
                <span id="balance-status" class="fw-bold">Waiting for lines...</span>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Create Entry</button>
                <a href="<?php echo e(route('ledger.entries.index')); ?>" class="btn btn-outline-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let lineIndex = 1;
function addLineRow() {
    const container = document.getElementById('ledger-lines');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 line-row';
    row.innerHTML = `
        <div class="col-md-3">
            <select name="lines[${lineIndex}][account_id]" class="form-select" required>
                <option value="">Select Account</option>
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($acct->id); ?>"><?php echo e($acct->code); ?> — <?php echo e($acct->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="lines[${lineIndex}][player_id]" class="form-select">
                <option value="">All/General</option>
                <?php $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="lines[${lineIndex}][debit]" class="form-control debit-input" placeholder="Debit $" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <input type="number" name="lines[${lineIndex}][credit]" class="form-control credit-input" placeholder="Credit $" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.line-row').remove(); updateBalance();">✕</button>
        </div>`;
    container.appendChild(row);
    lineIndex++;
}

// Auto-mutual exclusion: clear debit when credit entered, and vice versa
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('debit-input') && e.target.value) {
        const row = e.target.closest('.line-row');
        row.querySelector('.credit-input').value = '';
    }
    if (e.target.classList.contains('credit-input') && e.target.value) {
        const row = e.target.closest('.line-row');
        row.querySelector('.debit-input').value = '';
    }
    updateBalance();
});

function updateBalance() {
    let totalDebit = 0, totalCredit = 0;
    document.querySelectorAll('.debit-input').forEach(el => totalDebit += parseFloat(el.value) || 0);
    document.querySelectorAll('.credit-input').forEach(el => totalCredit += parseFloat(el.value) || 0);

    document.getElementById('total-debit').textContent = '$' + totalDebit.toFixed(2);
    document.getElementById('total-credit').textContent = '$' + totalCredit.toFixed(2);

    const diff = Math.abs(totalDebit - totalCredit);
    const status = document.getElementById('balance-status');
    if (totalDebit === 0 && totalCredit === 0) {
        status.textContent = 'Add at least one line';
        status.style.color = '#6b7280';
    } else if (diff < 0.01) {
        status.textContent = '✅ Balanced';
        status.style.color = '#16a34a';
    } else {
        status.textContent = '❌ Not balanced (diff: $' + diff.toFixed(2) + ')';
        status.style.color = '#dc2626';
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/ledger/entries/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Player Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><?php echo e($player->preferred_name ?? $player->name); ?></h1>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge-status badge-<?php echo e($player->status->value); ?>"><?php echo e(ucfirst($player->status->value)); ?></span>
            <small class="text-muted">Player #<?php echo e($player->id); ?></small>
            <?php if($player->isExcluded()): ?>
                <span class="badge bg-danger">Excluded</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('players.edit', $player)); ?>" class="btn btn-outline-primary">✏️ Edit</a>
        <form method="POST" action="<?php echo e(route('players.contacted', $player)); ?>" class="d-inline">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline-success">📲 Contacted</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Info Card -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white"><strong>📋 Details</strong></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted">Email</td><td><?php echo e($player->email ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Phone</td><td><?php echo e($player->phone ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Referral</td><td><?php echo e($player->referral_source ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Agent</td><td><?php echo e($player->agent?->name ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Admin</td><td><?php echo e($player->assignedAdmin?->name ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Risk Status</td><td><?php echo e($player->risk_status?->value ?? 'low'); ?></td></tr>
                    <tr><td class="text-muted">Compliance</td><td><?php echo $player->compliance_complete ? '✅ Complete' : '⏳ Pending'; ?></td></tr>
                    <tr><td class="text-muted">Last Played</td><td><?php echo e($player->last_played_at?->diffForHumans() ?? 'Never'); ?></td></tr>
                    <tr><td class="text-muted">Last Contact</td><td><?php echo e($player->last_contacted_at?->diffForHumans() ?? 'Never'); ?></td></tr>
                    <?php if(\App\ClubOpsEdition::isPro()): ?>
                    <tr><td class="text-muted">Portal Access</td>
                        <td>
                            <?php if($player->can_login): ?>
                                <span class="text-success">✅ Enabled</span>
                                <br><small class="text-muted">Last login: <?php echo e($player->last_login_at?->diffForHumans() ?? 'Never'); ?></small>
                            <?php else: ?>
                                <span class="text-muted">Disabled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $player)): ?> <?php if(!$player->can_login && \App\ClubOpsEdition::isPro()): ?>
                    <button class="btn btn-sm btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#portalModal">
                        🔓 Enable Player Portal
                    </button>
                <?php endif; ?> <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Balance & Platform Accounts -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><strong>💰 Balance</strong></div>
            <div class="card-body text-center">
                <div style="font-size:2.5rem;font-weight:800;color:<?php echo e($balance >= 0 ? '#16a34a' : '#dc2626'); ?>;">
                    $<?php echo e(number_format(abs($balance), 2)); ?>

                </div>
                <div class="text-muted"><?php echo e($balance >= 0 ? 'Positive' : 'Negative'); ?></div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong>🎮 Platform Accounts</strong>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#platformModal">+ Add</button>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $player->platformAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between py-1">
                        <span><strong><?php echo e($acct->platform); ?></strong>: <?php echo e($acct->username); ?></span>
                        <span><?php echo $acct->verified ? '✅' : '⏳'; ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted">No platform accounts linked.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tags -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🏷️ Tags</strong></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <?php $__currentLoopData = $player->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-secondary"><?php echo e($tag->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <form method="POST" action="<?php echo e(route('players.tags.store', $player)); ?>" class="input-group input-group-sm">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="tag" class="form-control" placeholder="New tag">
                    <button class="btn btn-outline-primary">Add</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notes Timeline -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📝 Notes</strong></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('players.notes.store', $player)); ?>" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" name="note" class="form-control" placeholder="Add a note..." required>
                        <button class="btn btn-primary">+ Add</button>
                    </div>
                </form>
                <div style="max-height:300px;overflow-y:auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $player->notes->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-bottom pb-2 mb-2">
                            <small class="text-muted"><?php echo e($note->agent?->name); ?> · <?php echo e($note->created_at->diffForHumans()); ?></small>
                            <div><?php echo e($note->note); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted">No notes yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Flags -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>⚠️ Risk Flags</strong></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $player->riskFlags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom pb-2 mb-2">
                        <span class="badge bg-<?php echo e($flag->severity === 'high' ? 'danger' : ($flag->severity === 'critical' ? 'dark' : 'warning')); ?>"><?php echo e($flag->type); ?></span>
                        <span class="text-muted small"><?php echo e($flag->created_at->diffForHumans()); ?></span>
                        <div class="mt-1"><?php echo e($flag->description); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted">No risk flags.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Promo Redemptions -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🎁 Promo History</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Promo</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $player->promoRedemptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($r->promotion?->name); ?></td>
                            <td>$<?php echo e(number_format($r->amount, 2)); ?></td>
                            <td><?php echo e($r->status); ?></td>
                            <td><?php echo e($r->claimed_at->format('M d')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-muted text-center">No redemptions.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tickets -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>🎫 Support Tickets</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th>Subject</th><th>Type</th><th>Priority</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $player->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="<?php echo e(route('tickets.show', $ticket)); ?>"><?php echo e($ticket->ticket_number); ?></a></td>
                            <td><?php echo e($ticket->subject); ?></td>
                            <td><?php echo e(str_replace('_', ' ', $ticket->type->value)); ?></td>
                            <td><span class="badge bg-<?php echo e($ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info')); ?>"><?php echo e($ticket->priority); ?></span></td>
                            <td><?php echo e($ticket->status->value); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-muted text-center">No tickets.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if(\App\ClubOpsEdition::isPro()): ?>
<!-- Enable Portal Modal -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $player)): ?>
<div class="modal fade" id="portalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary">
            <form method="POST" action="<?php echo e(route('players.enable-portal', $player)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">🔓 Enable Player Portal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        This allows <strong><?php echo e($player->name); ?></strong> to log in and view their
                        balance, transactions, promotions, and support tickets.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Set a Password</label>
                        <input type="password" name="password" class="form-control bg-dark text-light border-secondary"
                               placeholder="At least 8 characters" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-dark text-light border-secondary"
                               placeholder="Same password again" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Enable Portal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/players/show.blade.php ENDPATH**/ ?>
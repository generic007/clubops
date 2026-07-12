<?php $__env->startSection('title', 'Team & Invitations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4" style="max-width: 960px;">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <?php if(session('invitation_url')): ?>
                <div class="mt-2 p-2 bg-dark rounded" style="font-family: monospace; font-size: 0.85rem; word-break: break-all;">
                    📎 Invitation link: <a href="<?php echo e(session('invitation_url')); ?>" class="text-info"><?php echo e(session('invitation_url')); ?></a>
                    <br><small class="text-muted">(Copy this to share with the invitee. Email sending coming soon.)</small>
                </div>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">👥 Team</h1>
            <p class="text-muted mb-0"><?php echo e($club->name); ?> — Manage who has access to your club.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
            + Invite Member
        </button>
    </div>

    <!-- Current Team Members -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Club Members</span>
            <span class="badge bg-secondary"><?php echo e($agents->count()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: 700;">
                                            <?php echo e(substr($member->name, 0, 1)); ?>

                                        </div>
                                        <strong><?php echo e($member->name); ?></strong>
                                        <?php if($member->role === \App\Enums\AgentRole::Owner): ?>
                                            <span class="badge bg-warning text-dark">Owner</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo e($member->email); ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo e(ucfirst($member->role->value)); ?></span></td>
                                <td>
                                    <?php if($member->active): ?>
                                        <span class="text-success">● Active</span>
                                    <?php else: ?>
                                        <span class="text-danger">● Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo e($member->created_at->format('M j, Y')); ?></td>
                                <td>
                                    <?php if(auth()->user()->role === \App\Enums\AgentRole::Owner && $member->role !== \App\Enums\AgentRole::Owner): ?>
                                        <form action="<?php echo e(route('invitations.remove-agent', $member)); ?>" method="POST"
                                              onsubmit="return confirm('Remove <?php echo e($member->name); ?> from the club?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No members yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Invitations -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Pending Invitations</span>
            <span class="badge bg-secondary"><?php echo e($invitations->whereNull('accepted_at')->count()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Invited By</th>
                            <th>Sent</th>
                            <th>Expires</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $invitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($inv->email); ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo e(ucfirst($inv->role->value)); ?></span></td>
                                <td class="text-muted"><?php echo e($inv->inviter?->name ?? '—'); ?></td>
                                <td class="text-muted"><?php echo e($inv->created_at->diffForHumans()); ?></td>
                                <td>
                                    <?php if($inv->accepted_at): ?>
                                        <span class="text-success">Accepted <?php echo e($inv->accepted_at->diffForHumans()); ?></span>
                                    <?php elseif($inv->isExpired()): ?>
                                        <span class="text-danger">Expired</span>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e($inv->expires_at->diffForHumans()); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (! ($inv->accepted_at)): ?>
                                        <form action="<?php echo e(route('invitations.destroy', $inv)); ?>" method="POST"
                                              onsubmit="return confirm('Cancel this invitation?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No invitations yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary">
            <form method="POST" action="<?php echo e(route('invitations.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Invite a Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark text-light border-secondary"
                               placeholder="colleague@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select bg-dark text-light border-secondary">
                            <option value="manager">Manager</option>
                            <option value="agent" selected>Agent</option>
                            <option value="support">Support</option>
                            <option value="accountant">Accountant</option>
                            <option value="auditor">Auditor</option>
                        </select>
                        <div class="form-text text-muted mt-1">
                            <strong>Manager</strong> — Full operational access<br>
                            <strong>Agent</strong> — Manage assigned players<br>
                            <strong>Support</strong> — Handle tickets &amp; notes<br>
                            <strong>Accountant</strong> — Ledger &amp; reports only<br>
                            <strong>Auditor</strong> — Read-only access to everything
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personal Message <small class="text-muted">(optional)</small></label>
                        <textarea name="message" class="form-control bg-dark text-light border-secondary"
                                  rows="2" placeholder="Hey, join our club!"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/agents/invitations.blade.php ENDPATH**/ ?>
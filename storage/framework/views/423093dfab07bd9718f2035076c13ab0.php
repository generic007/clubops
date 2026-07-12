<?php $__env->startSection('title', $ticket->ticket_number . ' - ' . $ticket->subject); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        🎫 <?php echo e($ticket->ticket_number); ?>

        <span class="badge-status badge-<?php echo e($ticket->status->value); ?> ms-2"><?php echo e(ucfirst(str_replace('_', ' ', $ticket->status->value))); ?></span>
    </h1>
    <div>
        <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <!-- Ticket Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><strong>📋 <?php echo e($ticket->subject); ?></strong></div>
            <div class="card-body">
                <p><?php echo e($ticket->description ?? 'No description provided.'); ?></p>

                <dl class="row mb-0 mt-3">
                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9"><span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $ticket->type->value)); ?></span></dd>

                    <dt class="col-sm-3">Priority</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-<?php echo e($ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info')); ?>">
                            <?php echo e(ucfirst($ticket->priority)); ?>

                        </span>
                    </dd>

                    <dt class="col-sm-3">Player</dt>
                    <dd class="col-sm-9">
                        <?php if($ticket->player): ?>
                            <a href="<?php echo e(route('players.show', $ticket->player)); ?>"><?php echo e($ticket->player->name); ?></a>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3">Assigned To</dt>
                    <dd class="col-sm-9"><?php echo e($ticket->assignedTo?->name ?? 'Unassigned'); ?></dd>

                    <dt class="col-sm-3">Created</dt>
                    <dd class="col-sm-9"><?php echo e($ticket->created_at->format('M d, Y g:i A')); ?></dd>

                    <?php if($ticket->resolved_at): ?>
                    <dt class="col-sm-3">Resolved</dt>
                    <dd class="col-sm-9"><?php echo e($ticket->resolved_at->format('M d, Y g:i A')); ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Comments Thread -->
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>💬 Comments</strong></div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <strong><?php echo e($comment->author?->name ?? 'System'); ?></strong>
                            <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                        </div>
                        <p class="mb-0 mt-1"><?php echo e($comment->body); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-muted">No comments yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Comment Form -->
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('tickets.comments.store', $ticket)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <textarea name="body" class="form-control" rows="2" placeholder="Add a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Attachments -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white"><strong>📎 Attachments</strong></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small"><?php echo e($attachment->original_filename); ?></span>
                        <a href="<?php echo e(route('attachments.download', $attachment)); ?>" class="btn btn-sm btn-outline-primary">Download</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0 small">No attachments</p>
                <?php endif; ?>
                <hr>
                <form method="POST" action="<?php echo e(route('attachments.upload')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="attachable_type" value="<?php echo e(get_class($ticket)); ?>">
                    <input type="hidden" name="attachable_id" value="<?php echo e($ticket->id); ?>">
                    <div class="mb-2">
                        <input type="file" name="file" class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Upload</button>
                </form>
            </div>
        </div>

        <!-- Status Management -->
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>⚙️ Update Status</strong></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('tickets.update', $ticket)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="mb-2">
                        <select name="status" class="form-select form-select-sm">
                            <?php $__currentLoopData = \App\Enums\TicketStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->value); ?>" <?php echo e($ticket->status->value == $s->value ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $s->value))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="assigned_to" class="form-select form-select-sm">
                            <option value="">Unassigned</option>
                            <?php $__currentLoopData = \App\Models\Agent::where('active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agent->id); ?>" <?php echo e($ticket->assigned_to == $agent->id ? 'selected' : ''); ?>>
                                    <?php echo e($agent->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/tickets/show.blade.php ENDPATH**/ ?>
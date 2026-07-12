<?php $__env->startSection('title', 'Tickets'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎫 Support Tickets</h1>
    <div class="d-flex gap-2 align-items-center">
        @x('export-button', ['route' => route('tickets.export')])
        <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-primary">+ New Ticket</a>
    </div>
</div>

<!-- Search + Filters -->
<div class="d-flex gap-3 align-items-start mb-4 flex-wrap">
    @x('search-bar', ['route' => route('tickets.index'), 'placeholder' => 'Search tickets...'])
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('tickets.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = \App\Enums\TicketStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->value); ?>" <?php echo e(request('status') == $s->value ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst(str_replace('_', ' ', $s->value))); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All</option>
                    <option value="low" <?php echo e(request('priority') === 'low' ? 'selected' : ''); ?>>Low</option>
                    <option value="medium" <?php echo e(request('priority') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                    <option value="high" <?php echo e(request('priority') === 'high' ? 'selected' : ''); ?>>High</option>
                    <option value="urgent" <?php echo e(request('priority') === 'urgent' ? 'selected' : ''); ?>>Urgent</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = \App\Enums\TicketType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->value); ?>" <?php echo e(request('type') == $t->value ? 'selected' : ''); ?>>
                            <?php echo e(str_replace('_', ' ', ucfirst($t->value))); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Skeleton -->
<div id="loading-tickets" x-data x-init="$el.remove()">
    @x('skeleton-table', ['rows' => 5, 'cols' => 8])
</div>

<!-- Tickets Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Player</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($ticket->ticket_number); ?></td>
                        <td>
                            <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="text-decoration-none">
                                <?php echo e(\Illuminate\Support\Str::limit($ticket->subject, 40)); ?>

                            </a>
                        </td>
                        <td>
                            <?php if($ticket->player): ?>
                                <a href="<?php echo e(route('players.show', $ticket->player)); ?>"><?php echo e($ticket->player->name); ?></a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-secondary"><?php echo e(str_replace('_', ' ', $ticket->type->value)); ?></span></td>
                        <td>
                            <span class="badge bg-<?php echo e($ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst($ticket->priority)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge-status badge-<?php echo e($ticket->status->value); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $ticket->status->value))); ?>

                            </span>
                        </td>
                        <td><?php echo e($ticket->assignedTo?->name ?? '—'); ?></td>
                        <td>
                            <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No tickets found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($tickets->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($tickets->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/tickets/index.blade.php ENDPATH**/ ?>
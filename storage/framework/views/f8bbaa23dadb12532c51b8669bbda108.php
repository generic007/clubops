<?php $__env->startSection('title', 'Agent Exposure'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🤝 Agent Exposure Report</h1>
    <a href="<?php echo e(route('reports.agent-exposure')); ?>?csv=1" class="btn btn-outline-success">📥 CSV</a>
</div>

<div class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.agent-exposure')); ?>" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Agent</label>
                    <select name="agent" class="form-select">
                        <option value="">All Agents</option>
                        <?php $__currentLoopData = $allAgents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($a->id); ?>" <?php echo e(request('agent') == $a->id ? 'selected' : ''); ?>>
                                <?php echo e($a->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__empty_1 = true; $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong><?php echo e($agent->name); ?></strong>
            <span class="badge bg-<?php echo e($agent->role->value === 'agent' ? 'info' : 'warning'); ?>">
                <?php echo e(ucfirst($agent->role->value)); ?>

            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th>Last Played</th>
                            <th>Risk Status</th>
                            <th>Risk Flags</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $agentBalance = 0; $agentFlags = 0; ?>
                        <?php $__empty_2 = true; $__currentLoopData = $agent->players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                $bal = $player->balance();
                                $agentBalance += $bal;
                                $flagCount = $player->riskFlags->where('status', 'open')->count();
                                $agentFlags += $flagCount;
                            ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('players.show', $player)); ?>"><?php echo e($player->name); ?></a>
                            </td>
                            <td><span class="badge-status badge-<?php echo e($player->status->value); ?>"><?php echo e(ucfirst($player->status->value)); ?></span></td>
                            <td class="<?php echo e($bal < 0 ? 'text-danger' : ''); ?>">$<?php echo e(number_format($bal, 2)); ?></td>
                            <td><?php echo e($player->last_played_at?->diffForHumans() ?? 'Never'); ?></td>
                            <td>
                                <?php if($player->risk_status): ?>
                                    <span class="badge bg-<?php echo e($player->risk_status->value === 'high' ? 'danger' : ($player->risk_status->value === 'medium' ? 'warning' : 'info')); ?>">
                                        <?php echo e(ucfirst($player->risk_status->value)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($flagCount > 0 ? "🚩 {$flagCount}" : '—'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="6" class="text-center py-3 text-muted">No players assigned</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total (<?php echo e($agent->players->count()); ?> players)</td>
                            <td></td>
                            <td class="<?php echo e($agentBalance < 0 ? 'text-danger' : ''); ?>">
                                $<?php echo e(number_format($agentBalance, 2)); ?>

                            </td>
                            <td></td>
                            <td></td>
                            <td><?php echo e($agentFlags > 0 ? "🚩 {$agentFlags}" : '—'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-4 text-muted">
            No agents found.
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/reports/agent-exposure.blade.php ENDPATH**/ ?>
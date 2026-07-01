<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function log(
        Agent $agent,
        string $action,
        Model $auditable,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
    ): AuditLog {
        return AuditLog::create([
            'agent_id' => $agent->id,
            'action' => $action,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id ?? 0,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'description' => $description,
        ]);
    }

    public function logSensitiveAction(
        Agent $agent,
        string $action,
        Model $auditable,
        array $context,
    ): AuditLog {
        return $this->log(
            $agent,
            $action,
            $auditable,
            null,
            $context,
            "[SENSITIVE] {$action} on " . class_basename($auditable) . " #{$auditable->id}"
        );
    }
}

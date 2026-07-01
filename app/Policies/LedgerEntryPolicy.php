<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\LedgerEntry;
use App\Enums\AgentRole;

class LedgerEntryPolicy
{
    public function viewAny(Agent $agent): bool
    {
        return !$agent->isSupport();
    }

    public function view(Agent $agent, LedgerEntry $entry): bool
    {
        return !$agent->isSupport();
    }

    public function create(Agent $agent): bool
    {
        return in_array($agent->role, [
            AgentRole::Owner,
            AgentRole::Manager,
            AgentRole::Accountant,
        ]);
    }

    public function void(Agent $agent, LedgerEntry $entry): bool
    {
        return $agent->isOwner();
    }
}

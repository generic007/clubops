<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\Player;
use App\Enums\AgentRole;

class PlayerPolicy
{
    public function viewAny(Agent $agent): bool
    {
        return true;
    }

    public function view(Agent $agent, Player $player): bool
    {
        if ($agent->isOwner() || $agent->isManager() || $agent->isAuditor()) return true;
        if ($agent->isAgent() && $agent->id === $player->agent_id) return true;
        if ($agent->isAgent() && $agent->id === $player->assigned_admin_id) return true;
        return false;
    }

    public function create(Agent $agent): bool
    {
        return in_array($agent->role, [AgentRole::Owner, AgentRole::Manager, AgentRole::Agent]);
    }

    public function update(Agent $agent, Player $player): bool
    {
        if ($agent->isOwner() || $agent->isManager()) return true;
        if ($agent->isAgent()) {
            return $agent->id === $player->agent_id || $agent->id === $player->assigned_admin_id;
        }
        return false;
    }

    public function delete(Agent $agent, Player $player): bool
    {
        return $agent->isOwner();
    }

    public function viewFinancials(Agent $agent, Player $player): bool
    {
        return $agent->isOwner() || $agent->isManager() || $agent->isAccountant();
    }

    public function manageStatus(Agent $agent, Player $player): bool
    {
        return $agent->isOwner() || $agent->isManager();
    }
}

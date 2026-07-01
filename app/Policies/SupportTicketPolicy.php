<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\SupportTicket;
use App\Enums\AgentRole;

class SupportTicketPolicy
{
    public function viewAny(Agent $agent): bool
    {
        return true;
    }

    public function view(Agent $agent, SupportTicket $ticket): bool
    {
        return true; // Tickets are collaboration tools
    }

    public function create(Agent $agent): bool
    {
        return true; // All roles can create tickets
    }

    public function update(Agent $agent, SupportTicket $ticket): bool
    {
        return $agent->isOwner() || $agent->isManager() || $agent->isSupport()
            || ($agent->isAgent() && $ticket->assigned_to === $agent->id);
    }

    public function assign(Agent $agent, SupportTicket $ticket): bool
    {
        return $agent->isOwner() || $agent->isManager();
    }

    public function resolve(Agent $agent, SupportTicket $ticket): bool
    {
        return $agent->isOwner() || $agent->isManager() || $agent->isSupport()
            || ($agent->isAgent() && $ticket->assigned_to === $agent->id);
    }
}

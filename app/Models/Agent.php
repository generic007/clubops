<?php

namespace App\Models;

use App\Enums\AgentRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'active', 'created_by',
    ];

    protected $casts = [
        'role' => AgentRole::class,
        'active' => 'boolean',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'agent_id');
    }

    public function assignedPlayers(): HasMany
    {
        return $this->hasMany(Player::class, 'assigned_admin_id');
    }

    public function createdNotes(): HasMany
    {
        return $this->hasMany(PlayerNote::class, 'agent_id');
    }

    public function createdEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class, 'created_by');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }

    public function isOwner(): bool
    {
        return $this->role === AgentRole::Owner;
    }

    public function isManager(): bool
    {
        return $this->role === AgentRole::Manager;
    }

    public function isAccountant(): bool
    {
        return $this->role === AgentRole::Accountant;
    }

    public function isAuditor(): bool
    {
        return $this->role === AgentRole::Auditor;
    }

    public function isAgent(): bool
    {
        return $this->role === AgentRole::Agent;
    }

    public function isSupport(): bool
    {
        return $this->role === AgentRole::Support;
    }

    public function canViewPlayerFinancials(): bool
    {
        return in_array($this->role, [AgentRole::Owner, AgentRole::Manager, AgentRole::Accountant]);
    }
}

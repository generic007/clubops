<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Club extends Model
{
    protected $fillable = [
        'name', 'slug', 'contact_email', 'contact_phone',
        'description', 'timezone', 'currency', 'active', 'settings',
        'encrypted_club_key', 'server_encrypted_club_key', 'single_club',
        'stripe_id', 'pm_type', 'pm_last_four',
        'trial_ends_at', 'subscription_plan_id',
        'subscription_ends_at', 'subscription_status',
    ];

    protected $casts = [
        'active' => 'boolean',
        'single_club' => 'boolean',
        'settings' => 'json',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Club $club) {
            if (empty($club->slug)) {
                $base = Str::slug($club->name);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $club->slug = $slug;
            }
        });
    }

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(ClubInvitation::class);
    }

    public function owner(): ?Agent
    {
        return $this->agents()->where('role', \App\Enums\AgentRole::Owner)->first();
    }

    public function activeAgents(): HasMany
    {
        return $this->agents()->where('active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOwnedBy($query, Agent $agent)
    {
        return $query->whereHas('agents', fn($q) =>
            $q->where('id', $agent->id)->where('role', \App\Enums\AgentRole::Owner)
        );
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->subscription_status === 'active' && $this->subscription_ends_at && $this->subscription_ends_at->isFuture()) {
            return true;
        }
        if ($this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return true;
        }
        return false;
    }
}

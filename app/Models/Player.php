<?php

namespace App\Models;

use App\Enums\PlayerStatus;
use App\Enums\RiskLevel;
use App\Models\Club;
use App\Models\Traits\Encryptable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Authenticatable
{
    use SoftDeletes;
    use Encryptable;

    protected array $encryptable = ['name', 'preferred_name', 'phone', 'email', 'notes'];

    protected $fillable = [
        'name', 'preferred_name', 'phone', 'email', 'password', 'can_login',
        'status', 'referral_source', 'agent_id', 'assigned_admin_id',
        'risk_status', 'last_contacted_at', 'last_played_at',
        'compliance_complete', 'notes', 'preferred_game', 'preferred_stakes',
        'club_id',
    ];

    protected $casts = [
        'status' => PlayerStatus::class,
        'compliance_complete' => 'boolean',
        'can_login' => 'boolean',
        'risk_status' => RiskLevel::class,
        'last_contacted_at' => 'datetime',
        'last_played_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function platformAccounts(): HasMany
    {
        return $this->hasMany(PlayerPlatformAccount::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'player_tag');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(PlayerNote::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_admin_id');
    }

    public function ledgerLines(): HasMany
    {
        return $this->hasMany(LedgerLine::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function promoRedemptions(): HasMany
    {
        return $this->hasMany(PromotionRedemption::class);
    }

    public function riskFlags(): HasMany
    {
        return $this->hasMany(RiskFlag::class);
    }

    public function compliance(): HasOne
    {
        return $this->hasOne(ComplianceProfile::class);
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(Exclusion::class);
    }

    public function balance(): float
    {
        $credit = $this->ledgerLines()->sum('credit');
        $debit = $this->ledgerLines()->sum('debit');
        return (float) ($credit - $debit);
    }

    public function lifetimeVolume(): float
    {
        return (float) $this->ledgerLines()
            ->selectRaw('COALESCE(SUM(debit), 0) + COALESCE(SUM(credit), 0) as volume')
            ->value('volume');
    }

    public function lifetimeProfitLoss(): float
    {
        $credits = (float) $this->ledgerLines()
            ->whereHas('entry', fn($q) => $q->where('type', '!=', \App\Enums\TransactionType::Void->value))
            ->sum('credit');
        $debits = (float) $this->ledgerLines()
            ->whereHas('entry', fn($q) => $q->where('type', '!=', \App\Enums\TransactionType::Void->value))
            ->sum('debit');
        return $credits - $debits;
    }

    public function isExcluded(): bool
    {
        return $this->exclusions()
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->exists();
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            PlayerStatus::Active,
            PlayerStatus::Vip,
        ]);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', PlayerStatus::Inactive);
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_status', [
            RiskLevel::High,
            RiskLevel::Critical,
        ]);
    }

    public function scopeByAgent($query, Agent $agent)
    {
        return $query->where('agent_id', $agent->id);
    }
}

<?php

namespace App\Models;

use App\Enums\PromoType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'type', 'description', 'value', 'cap',
        'total_liability', 'claimed_liability', 'starts_at', 'ends_at',
        'active', 'terms', 'eligibility_rules',
    ];

    protected $casts = [
        'type' => PromoType::class,
        'value' => 'decimal:2',
        'cap' => 'decimal:2',
        'total_liability' => 'decimal:2',
        'claimed_liability' => 'decimal:2',
        'active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'eligibility_rules' => 'json',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(PromotionRedemption::class);
    }

    public function remainingCap(): float
    {
        if (!$this->cap) return PHP_FLOAT_MAX;
        return (float) ($this->cap - $this->claimed_liability);
    }

    public function isActive(): bool
    {
        return $this->active
            && $this->starts_at <= now()
            && ($this->ends_at === null || $this->ends_at >= now());
    }
}

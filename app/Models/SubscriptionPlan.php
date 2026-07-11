<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'monthly_price_cents', 'yearly_price_cents',
        'stripe_monthly_price_id', 'stripe_yearly_price_id',
        'features', 'tier', 'active',
    ];

    protected $casts = [
        'features' => 'json',
        'active' => 'boolean',
        'monthly_price_cents' => 'integer',
        'yearly_price_cents' => 'integer',
        'tier' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function monthlyPrice(): string
    {
        return '$' . number_format($this->monthly_price_cents / 100, 0);
    }

    public function yearlyPrice(): ?string
    {
        return $this->yearly_price_cents
            ? '$' . number_format($this->yearly_price_cents / 100, 0)
            : null;
    }

    public function monthlyPricePerMonth(): ?string
    {
        if (!$this->yearly_price_cents) {
            return null;
        }
        return '$' . number_format($this->yearly_price_cents / 12 / 100, 0);
    }
}

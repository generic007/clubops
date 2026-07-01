<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LedgerAccount extends Model
{
    protected $fillable = [
        'code', 'name', 'type', 'currency', 'balance', 'active', 'description',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(LedgerLine::class, 'account_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function recalculateBalance(): void
    {
        $debit = $this->lines()->sum('debit');
        $credit = $this->lines()->sum('credit');
        $this->update(['balance' => $credit - $debit]);
    }
}

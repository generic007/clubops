<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Perfect for new clubs — member management, immutable ledger, real-time reconciliation, and basic reporting.',
                'monthly_price_cents' => 9900,
                'yearly_price_cents' => 99000,
                'features' => json_encode([
                    '1 club',
                    'Up to 5 staff accounts',
                    'Up to 500 members',
                    'Immutable transaction ledger',
                    'Real-time reconciliation',
                    'Basic reports',
                    'Email support',
                ]),
                'tier' => 1,
                'active' => true,
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'description' => 'For established clubs — everything in Starter plus unlimited staff, automated compliance, and priority support.',
                'monthly_price_cents' => 19900,
                'yearly_price_cents' => 199000,
                'features' => json_encode([
                    '1 club',
                    'Unlimited staff accounts',
                    'Unlimited members',
                    'Everything in Starter, plus',
                    'Automated compliance reports',
                    'Promotional engine',
                    'API access',
                    'Priority support (phone + email)',
                ]),
                'tier' => 2,
                'active' => true,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\LedgerAccount;
use Illuminate\Database\Seeder;

class LedgerAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Operating Cash', 'type' => 'asset', 'description' => 'Main club operating account'],
            ['code' => '2000', 'name' => 'Player Funds', 'type' => 'liability', 'description' => 'Player float/deposit liability'],
            ['code' => '2100', 'name' => 'Promo Liability', 'type' => 'liability', 'description' => 'Outstanding promo credit liability'],
            ['code' => '3000', 'name' => 'Club Equity', 'type' => 'equity', 'description' => 'Club owner equity'],
            ['code' => '4000', 'name' => 'Revenue — Rake', 'type' => 'income', 'description' => 'Rake/take revenue'],
            ['code' => '4100', 'name' => 'Revenue — Promotional', 'type' => 'income', 'description' => 'Promo-related revenue adjustments'],
            ['code' => '5000', 'name' => 'Expense — Operations', 'type' => 'expense', 'description' => 'General operating expenses'],
            ['code' => '5100', 'name' => 'Expense — Promotions', 'type' => 'expense', 'description' => 'Cost of promotional activities'],
        ];

        foreach ($accounts as $acct) {
            LedgerAccount::create($acct);
        }

        $this->command->info('Chart of accounts seeded.');
    }
}

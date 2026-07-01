<?php

namespace App\Console\Commands;

use App\Models\LedgerAccount;
use Illuminate\Console\Command;

class RecalculateBalances extends Command
{
    protected $signature = 'clubops:recalculate-balances';
    protected $description = 'Recalculate all ledger account balances from scratch';

    public function handle(): int
    {
        $accounts = LedgerAccount::all();
        foreach ($accounts as $account) {
            $account->recalculateBalance();
        }
        $this->info("Recalculated balances for {$accounts->count()} accounts.");
        return Command::SUCCESS;
    }
}

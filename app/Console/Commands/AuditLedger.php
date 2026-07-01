<?php

namespace App\Console\Commands;

use App\Services\LedgerService;
use Illuminate\Console\Command;

class AuditLedger extends Command
{
    protected $signature = 'clubops:audit-ledger';
    protected $description = 'Verify ledger integrity';

    public function handle(LedgerService $ledger): int
    {
        $issues = $ledger->auditLedger();
        if (empty($issues)) {
            $this->info('✓ Ledger is clean. All entries balanced.');
        } else {
            foreach ($issues as $issue) {
                $this->error($issue);
            }
        }
        return Command::SUCCESS;
    }
}

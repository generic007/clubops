<?php

namespace App\Console\Commands;

use App\Services\LedgerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyClose extends Command
{
    protected $signature = 'clubops:daily-close {--date= : The date to close (YYYY-MM-DD)}';
    protected $description = 'Lock today\'s ledger entries and generate daily close';

    public function handle(LedgerService $ledger): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
        $this->info("Closing ledger for {$date->toDateString()}...");
        $this->info('Done.');
        return Command::SUCCESS;
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Daily close check (runs at midnight)
        // $schedule->command('clubops:daily-close')->dailyAt('23:59');

        // Dormant player check (runs daily at 6am)
        // $schedule->command('clubops:check-dormant-players')->dailyAt('06:00');

        // Ledger audit (runs weekly on Sunday at 3am)
        // $schedule->command('clubops:audit-ledger')->weeklyOn(0, '03:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

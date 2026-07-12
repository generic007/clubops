<?php

namespace App\Providers;

use App\Models\Player;
use App\Models\LedgerEntry;
use App\Models\SupportTicket;
use App\Policies\PlayerPolicy;
use App\Policies\LedgerEntryPolicy;
use App\Policies\SupportTicketPolicy;
use App\Services\LedgerService;
use App\Services\AuditService;
use App\Services\PlayerCrmService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind services as singletons
        $this->app->singleton(AuditService::class, function () {
            return new AuditService();
        });

        $this->app->singleton(LedgerService::class, function ($app) {
            return new LedgerService($app->make(AuditService::class));
        });

        $this->app->singleton(PlayerCrmService::class, function ($app) {
            return new PlayerCrmService($app->make(AuditService::class));
        });
    }

    public function boot(): void
    {
        // NIST SP 800-63B compliant password defaults
        Password::defaults(function () {
            return Password::min(8)
                ->max(64)
                ->uncompromised();
        });

        // Register policies
        Gate::policy(Player::class, PlayerPolicy::class);
        Gate::policy(LedgerEntry::class, LedgerEntryPolicy::class);
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);

        // Load offline route
        Route::middleware('web')
            ->group(base_path('routes/offline.php'));
    }
}

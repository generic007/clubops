<?php

use Illuminate\Support\Facades\Route;
use App\ClubOpsEdition;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerNoteController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PlayerAuthController;
use App\Http\Controllers\QuickEntryController;
use App\Http\Controllers\AgentCommissionController;
use App\Http\Controllers\BillingController;

// Landing page (guest-accessible)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Pro-only: Player portal
if (ClubOpsEdition::isPro()) {
    Route::prefix('player')->name('player.')->group(function () {
        Route::get('login', [PlayerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [PlayerAuthController::class, 'login']);

        Route::middleware('auth:player')->group(function () {
            Route::post('logout', [PlayerAuthController::class, 'logout'])->name('logout');
            Route::get('dashboard', [PlayerAuthController::class, 'dashboard'])->name('dashboard');
        });
    });

    // Pro-only: Team invitations (token-based, no auth needed for accept)
    Route::get('invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('invitations/accept/{token}/complete', [InvitationController::class, 'completeRegistration'])->name('invitations.complete');
}

// Billing routes (auth required, but NO subscription check)
Route::middleware(['auth'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/checkout/{plan}/{interval?}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/cancelled', [BillingController::class, 'cancelled'])->name('billing.cancelled');
});

// Authenticated admin area
Route::middleware(['auth', 'subscribed'])->group(function () {
    // Pro-only: Team & Invitations
    if (ClubOpsEdition::isPro()) {
        Route::get('team', [InvitationController::class, 'index'])->name('invitations.index');
        Route::post('invitations', [InvitationController::class, 'store'])->name('invitations.store');
        Route::delete('invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
        Route::delete('team/agents/{targetAgent}', [InvitationController::class, 'removeAgent'])->name('invitations.remove-agent');
    }

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Player CRM
    Route::resource('players', PlayerController::class);
    Route::get('players/export', [PlayerController::class, 'export'])->name('players.export');
    Route::post('players/{player}/notes', [PlayerNoteController::class, 'store'])->name('players.notes.store')
        ->middleware('can:update,player');
    Route::delete('players/{player}/notes/{note}', [PlayerNoteController::class, 'destroy'])->name('players.notes.destroy')
        ->middleware('can:update,player');
    Route::post('players/{player}/contacted', [PlayerController::class, 'markContacted'])->name('players.contacted')
        ->middleware('can:update,player');
    Route::post('players/{player}/tags', [PlayerController::class, 'addTag'])->name('players.tags.store')
        ->middleware('can:update,player');
    Route::delete('players/{player}/tags/{tag}', [PlayerController::class, 'removeTag'])->name('players.tags.destroy')
        ->middleware('can:update,player');

    // Dashboard quick actions
    Route::post('quick/buy-in', [QuickEntryController::class, 'buyIn'])->name('quick.buy-in');
    Route::post('quick/cash-out', [QuickEntryController::class, 'cashOut'])->name('quick.cash-out');
    Route::get('quick/search-players', [QuickEntryController::class, 'searchPlayers'])->name('quick.search-players');

    // Commissions / Rakeback (Pro only)
    if (ClubOpsEdition::isPro()) {
        Route::get('commissions', [AgentCommissionController::class, 'index'])->name('commissions.index');
        Route::post('commissions', [AgentCommissionController::class, 'store'])->name('commissions.store');
        Route::delete('commissions/{structure}', [AgentCommissionController::class, 'destroy'])->name('commissions.destroy');
        Route::post('commissions/{targetAgent}/settle', [AgentCommissionController::class, 'settle'])->name('commissions.settle');
    }

    // Pro-only: Player portal access
    if (ClubOpsEdition::isPro()) {
        Route::post('players/{player}/enable-portal', [PlayerController::class, 'enablePortal'])->name('players.enable-portal')
            ->middleware('role:owner,manager');
    }

    // Agents
    Route::resource('agents', AgentController::class)->middleware('role:owner,manager');
    Route::get('agents/export', [AgentController::class, 'export'])->name('agents.export')->middleware('role:owner,manager');

    // Ledger Accounts
    Route::resource('ledger/accounts', LedgerAccountController::class)
        ->middleware('role:owner,manager,accountant')
        ->names([
            'index' => 'ledger.accounts.index',
            'create' => 'ledger.accounts.create',
            'store' => 'ledger.accounts.store',
            'edit' => 'ledger.accounts.edit',
            'update' => 'ledger.accounts.update',
        ]);

    // Ledger Entries
    Route::middleware('role:owner,manager,accountant')->group(function () {
        Route::get('ledger/entries', [LedgerEntryController::class, 'index'])->name('ledger.entries.index');
        Route::get('ledger/entries/create', [LedgerEntryController::class, 'create'])->name('ledger.entries.create');
        Route::post('ledger/entries', [LedgerEntryController::class, 'store'])->name('ledger.entries.store');
        Route::get('ledger/entries/{entry}', [LedgerEntryController::class, 'show'])->name('ledger.entries.show');
        Route::post('ledger/entries/{entry}/void', [LedgerEntryController::class, 'void'])->name('ledger.entries.void')
            ->middleware('can:void,entry');
    });

    // Reconciliation
    Route::middleware('role:owner,manager')->group(function () {
        Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index');
        Route::get('reconciliations/create', [ReconciliationController::class, 'create'])->name('reconciliations.create');
        Route::post('reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store');
        Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show');
        Route::post('reconciliations/{reconciliation}/lock', [ReconciliationController::class, 'lock'])->name('reconciliations.lock');
    });

    // Promotions
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('promotions', PromotionController::class);
        Route::get('promotions/export', [PromotionController::class, 'export'])->name('promotions.export');
        Route::post('promotions/{promotion}/redeem/{player}', [PromotionController::class, 'redeem'])->name('promotions.redeem');
    });

    // Support Tickets
    Route::resource('tickets', SupportTicketController::class);
    Route::get('tickets/export', [SupportTicketController::class, 'export'])->name('tickets.export');
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');

    // Reports
    Route::middleware('role:owner,manager,accountant,auditor')->prefix('reports')->name('reports.')->group(function () {
        Route::get('player-statement/{player}', [ReportController::class, 'playerStatement'])->name('player-statement');
        Route::get('daily-ledger/{date?}', [ReportController::class, 'dailyLedger'])->name('daily-ledger');
        Route::get('daily-close/{date?}', [ReportController::class, 'dailyClose'])->name('daily-close');
        Route::get('promo-liability', [ReportController::class, 'promoLiability'])->name('promo-liability');
        Route::get('agent-exposure/{agent?}', [ReportController::class, 'agentExposure'])->name('agent-exposure');
        Route::get('open-disputes', [ReportController::class, 'openDisputes'])->name('open-disputes');
        Route::get('ledger-exceptions', [ReportController::class, 'ledgerExceptions'])->name('ledger-exceptions');
        Route::get('activity-by-platform', [ReportController::class, 'activityByPlatform'])->name('activity-by-platform');
    });

    // Imports
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('imports', ImportController::class);
        Route::post('imports/{import}/accept/{row}', [ImportController::class, 'acceptRow'])->name('imports.accept');
        Route::post('imports/{import}/skip/{row}', [ImportController::class, 'skipRow'])->name('imports.skip');
    });

    // Attachments
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::post('attachments/upload', [AttachmentController::class, 'store'])->name('attachments.upload');

    // Audit Log
    Route::get('audit-log', [AuditLogController::class, 'index'])
        ->middleware('role:owner,manager,auditor')
        ->name('audit-log');

    // Games
    Route::middleware('role:owner,manager,agent')->group(function () {
        Route::resource('games', GameController::class)->except(['destroy']);
        Route::get('games/export', [GameController::class, 'export'])->name('games.export');
        Route::post('games/{game}/sessions', [GameController::class, 'startSession'])->name('games.sessions.start');
        Route::put('games/sessions/{session}/end', [GameController::class, 'endSession'])->name('games.sessions.end');
        Route::delete('games/sessions/{session}', [GameController::class, 'destroySession'])->name('games.sessions.destroy');
    });
    Route::delete('games/{game}', [GameController::class, 'destroy'])->name('games.destroy')
        ->middleware('role:owner,manager');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index')
        ->middleware('role:owner,manager');

    // Compliance
    Route::middleware('role:owner,manager')->group(function () {
        Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
        Route::get('compliance/players/{player}', [ComplianceController::class, 'show'])->name('compliance.show');
        Route::post('compliance/players/{player}/exclude', [ComplianceController::class, 'excludePlayer'])->name('compliance.exclude');
        Route::post('compliance/players/{player}/reinstate', [ComplianceController::class, 'reinstatePlayer'])->name('compliance.reinstate');
    });
});

require __DIR__.'/auth.php';

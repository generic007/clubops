<?php

use Illuminate\Support\Facades\Route;
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

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard — all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Player CRM — any agent, but policies restrict within controller
    Route::resource('players', PlayerController::class);
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

    // Agents — owner/manager only
    Route::resource('agents', AgentController::class)->middleware('role:owner,manager');

    // Ledger Accounts — financial config, manager/accountant only
    Route::resource('ledger/accounts', LedgerAccountController::class)
        ->middleware('role:owner,manager,accountant')
        ->names([
            'index' => 'ledger.accounts.index',
            'create' => 'ledger.accounts.create',
            'store' => 'ledger.accounts.store',
            'edit' => 'ledger.accounts.edit',
            'update' => 'ledger.accounts.update',
        ]);

    // Ledger Entries — financial operations
    Route::middleware('role:owner,manager,accountant')->group(function () {
        Route::get('ledger/entries', [LedgerEntryController::class, 'index'])->name('ledger.entries.index');
        Route::get('ledger/entries/create', [LedgerEntryController::class, 'create'])->name('ledger.entries.create');
        Route::post('ledger/entries', [LedgerEntryController::class, 'store'])->name('ledger.entries.store');
        Route::get('ledger/entries/{entry}', [LedgerEntryController::class, 'show'])->name('ledger.entries.show');
        Route::post('ledger/entries/{entry}/void', [LedgerEntryController::class, 'void'])->name('ledger.entries.void')
            ->middleware('can:void,entry');
    });

    // Reconciliation — manager+ role only
    Route::middleware('role:owner,manager')->group(function () {
        Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index');
        Route::get('reconciliations/create', [ReconciliationController::class, 'create'])->name('reconciliations.create');
        Route::post('reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store');
        Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show');
        Route::post('reconciliations/{reconciliation}/lock', [ReconciliationController::class, 'lock'])->name('reconciliations.lock');
    });

    // Promotions — owner/manager only
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('promotions', PromotionController::class);
        Route::post('promotions/{promotion}/redeem/{player}', [PromotionController::class, 'redeem'])->name('promotions.redeem');
    });

    // Support Tickets — any agent, but policy checks within controller
    Route::resource('tickets', SupportTicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');

    // Reports — owner/manager/accountant/auditor only
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

    // Imports — owner/manager only (bulk data operations)
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('imports', ImportController::class);
        Route::post('imports/{import}/accept/{row}', [ImportController::class, 'acceptRow'])->name('imports.accept');
        Route::post('imports/{import}/skip/{row}', [ImportController::class, 'skipRow'])->name('imports.skip');
    });

    // Attachments — any agent, controller handles storage security
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::post('attachments/upload', [AttachmentController::class, 'store'])->name('attachments.upload');

    // Audit Log — owner/manager/auditor only
    Route::get('audit-log', [AuditLogController::class, 'index'])
        ->middleware('role:owner,manager,auditor')
        ->name('audit-log');

    // Games — owner/manager/agent can view, owner/manager can create
    Route::middleware('role:owner,manager,agent')->group(function () {
        Route::resource('games', GameController::class)->except(['destroy']);
        Route::post('games/{game}/sessions', [GameController::class, 'startSession'])->name('games.sessions.start');
    });
    Route::delete('games/{game}', [GameController::class, 'destroy'])->name('games.destroy')
        ->middleware('role:owner,manager');

    // Settings — owner/manager only
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index')
        ->middleware('role:owner,manager');

    // Compliance — owner/manager only (sensitive operations)
    Route::middleware('role:owner,manager')->group(function () {
        Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
        Route::get('compliance/players/{player}', [ComplianceController::class, 'show'])->name('compliance.show');
        Route::post('compliance/players/{player}/exclude', [ComplianceController::class, 'excludePlayer'])->name('compliance.exclude');
        Route::post('compliance/players/{player}/reinstate', [ComplianceController::class, 'reinstatePlayer'])->name('compliance.reinstate');
    });
});

require __DIR__.'/auth.php';

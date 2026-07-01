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

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Player CRM
    Route::resource('players', PlayerController::class);
    Route::post('players/{player}/notes', [PlayerNoteController::class, 'store'])->name('players.notes.store');
    Route::delete('players/{player}/notes/{note}', [PlayerNoteController::class, 'destroy'])->name('players.notes.destroy');
    Route::post('players/{player}/contacted', [PlayerController::class, 'markContacted'])->name('players.contacted');
    Route::post('players/{player}/tags', [PlayerController::class, 'addTag'])->name('players.tags.store');
    Route::delete('players/{player}/tags/{tag}', [PlayerController::class, 'removeTag'])->name('players.tags.destroy');

    // Agents
    Route::resource('agents', AgentController::class)->middleware('role:owner,manager');

    // Ledger
    Route::resource('ledger/accounts', LedgerAccountController::class)->names([
        'index' => 'ledger.accounts.index',
        'create' => 'ledger.accounts.create',
        'store' => 'ledger.accounts.store',
        'edit' => 'ledger.accounts.edit',
        'update' => 'ledger.accounts.update',
    ]);
    Route::get('ledger/entries', [LedgerEntryController::class, 'index'])->name('ledger.entries.index');
    Route::get('ledger/entries/create', [LedgerEntryController::class, 'create'])->name('ledger.entries.create');
    Route::post('ledger/entries', [LedgerEntryController::class, 'store'])->name('ledger.entries.store');
    Route::get('ledger/entries/{entry}', [LedgerEntryController::class, 'show'])->name('ledger.entries.show');
    Route::post('ledger/entries/{entry}/void', [LedgerEntryController::class, 'void'])->name('ledger.entries.void');

    // Reconciliation
    Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index');
    Route::get('reconciliations/create', [ReconciliationController::class, 'create'])->name('reconciliations.create');
    Route::post('reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store');
    Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show');
    Route::post('reconciliations/{reconciliation}/lock', [ReconciliationController::class, 'lock'])->name('reconciliations.lock');

    // Promotions
    Route::resource('promotions', PromotionController::class);
    Route::post('promotions/{promotion}/redeem/{player}', [PromotionController::class, 'redeem'])->name('promotions.redeem');

    // Support Tickets
    Route::resource('tickets', SupportTicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
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
    Route::resource('imports', ImportController::class);
    Route::post('imports/{import}/accept/{row}', [ImportController::class, 'acceptRow'])->name('imports.accept');
    Route::post('imports/{import}/skip/{row}', [ImportController::class, 'skipRow'])->name('imports.skip');

    // Attachments
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::post('attachments/upload', [AttachmentController::class, 'store'])->name('attachments.upload');

    // Audit Log
    Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log');

    // Compliance
    Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
    Route::get('compliance/players/{player}', [ComplianceController::class, 'show'])->name('compliance.show');
    Route::post('compliance/players/{player}/exclude', [ComplianceController::class, 'excludePlayer'])->name('compliance.exclude');
    Route::post('compliance/players/{player}/reinstate', [ComplianceController::class, 'reinstatePlayer'])->name('compliance.reinstate');
});

require __DIR__.'/auth.php';

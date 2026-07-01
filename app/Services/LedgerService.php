<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\LedgerLine;
use App\Models\Player;
use App\Models\Reconciliation;
use App\Models\ReconciliationItem;
use App\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LedgerService
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function createEntry(
        string $type,
        string $description,
        Agent $createdBy,
        array $lines,
        ?string $sourceType = null,
        ?int $sourceId = null,
        ?string $reference = null,
        ?Carbon $entryDate = null,
    ): LedgerEntry {
        return DB::transaction(function () use ($type, $description, $createdBy, $lines, $sourceType, $sourceId, $reference, $entryDate) {
            $date = $entryDate ?? Carbon::today();

            // Check if date is locked by daily close
            $this->ensureDateNotLocked($date);

            // Generate entry number
            $entryNumber = $this->generateEntryNumber($date);

            $entry = LedgerEntry::create([
                'entry_number' => $entryNumber,
                'type' => $type,
                'description' => $description,
                'created_by' => $createdBy->id,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'reference' => $reference,
                'entry_date' => $date,
                'locked' => false,
            ]);

            foreach ($lines as $line) {
                LedgerLine::create([
                    'entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'player_id' => $line['player_id'] ?? null,
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                ]);
            }

            // Verify balance
            if (!$entry->isBalanced()) {
                throw new \RuntimeException("Ledger entry #{$entry->id} is not balanced. Debits must equal credits.");
            }

            // Update account balances
            foreach ($lines as $line) {
                $account = LedgerAccount::find($line['account_id']);
                if ($account) {
                    $account->recalculateBalance();
                }
            }

            // Audit log
            $this->audit->log(
                $createdBy,
                'ledger_entry_created',
                $entry,
                null,
                ['entry_number' => $entryNumber, 'type' => $type, 'amount' => collect($lines)->sum('debit')],
                "Ledger entry {$entryNumber}: {$description}"
            );

            return $entry;
        });
    }

    public function voidEntry(LedgerEntry $entry, Agent $agent, string $reason): LedgerEntry
    {
        return DB::transaction(function () use ($entry, $agent, $reason) {
            if ($entry->locked) {
                throw new \RuntimeException("Cannot void a locked entry. The day has been closed.");
            }

            // Create reversal lines
            $reversalLines = $entry->lines->map(function ($line) {
                return [
                    'account_id' => $line->account_id,
                    'player_id' => $line->player_id,
                    'debit' => $line->credit,  // Swap debit/credit
                    'credit' => $line->debit,
                ];
            })->toArray();

            $reversal = $this->createEntry(
                TransactionType::Void->value,
                "Void of entry #{$entry->entry_number}: {$reason}",
                $agent,
                $reversalLines,
                null, null,
                "Reverses: {$entry->entry_number}",
                Carbon::today(),
            );

            $reversal->update(['reversed_entry_id' => $entry->id]);
            $entry->update(['locked' => true]);

            // Audit log
            $this->audit->log(
                $agent,
                'ledger_entry_voided',
                $entry,
                ['entry_number' => $entry->entry_number],
                ['reversal_entry' => $reversal->entry_number, 'reason' => $reason],
                "Voided entry {$entry->entry_number}: {$reason}"
            );

            return $reversal;
        });
    }

    public function getPlayerBalance(Player $player): float
    {
        return (float) LedgerLine::where('player_id', $player->id)
            ->selectRaw('COALESCE(SUM(credit), 0) - COALESCE(SUM(debit), 0) as balance')
            ->value('balance');
    }

    public function getAccountBalance(LedgerAccount $account, ?Carbon $asOf = null): float
    {
        $query = LedgerLine::where('account_id', $account->id);
        if ($asOf) {
            $query->whereHas('entry', fn($q) => $q->whereDate('entry_date', '<=', $asOf));
        }
        return (float) $query->selectRaw('COALESCE(SUM(credit), 0) - COALESCE(SUM(debit), 0) as balance')->value('balance');
    }

    public function dailyClose(Carbon $date, Agent $agent): void
    {
        DB::transaction(function () use ($date, $agent) {
            // Lock all entries for this date
            LedgerEntry::whereDate('entry_date', $date)
                ->where('locked', false)
                ->update(['locked' => true]);

            // Audit log
            $this->audit->log(
                $agent,
                'daily_close',
                new \stdClass(),
                null,
                ['date' => $date->toDateString()],
                "Daily close for {$date->toDateString()}"
            );
        });
    }

    public function reconcile(
        Carbon $date,
        float $platformTotal,
        Agent $agent,
    ): Reconciliation {
        return DB::transaction(function () use ($date, $platformTotal, $agent) {
            $ledgerTotal = (float) LedgerEntry::whereDate('entry_date', $date)
                ->where('type', '!=', TransactionType::Void->value)
                ->join('ledger_lines', 'ledger_entries.id', '=', 'ledger_lines.entry_id')
                ->selectRaw('COALESCE(SUM(ledger_lines.credit), 0) - COALESCE(SUM(ledger_lines.debit), 0) as total')
                ->value('total');

            $variance = $platformTotal - $ledgerTotal;

            $reconciliation = Reconciliation::create([
                'reconciliation_date' => $date,
                'status' => $variance > 0.01 ? 'in_progress' : 'complete',
                'platform_total' => $platformTotal,
                'ledger_total' => $ledgerTotal,
                'variance' => $variance,
                'created_by' => $agent->id,
            ]);

            // Create reconciliation items
            $entries = LedgerEntry::whereDate('entry_date', $date)->get();
            foreach ($entries as $entry) {
                $entryTotal = (float) $entry->lines()
                    ->selectRaw('COALESCE(SUM(credit), 0) - COALESCE(SUM(debit), 0) as total')
                    ->value('total');
                ReconciliationItem::create([
                    'reconciliation_id' => $reconciliation->id,
                    'entry_id' => $entry->id,
                    'amount' => $entryTotal,
                    'type' => 'matched',
                ]);
            }

            $this->audit->log(
                $agent,
                'reconciliation_created',
                $reconciliation,
                null,
                ['date' => $date->toDateString(), 'variance' => $variance],
                "Reconciliation for {$date->toDateString()}: variance \${$variance}"
            );

            return $reconciliation;
        });
    }

    public function auditLedger(): array
    {
        $issues = [];
        $entries = LedgerEntry::all();
        foreach ($entries as $entry) {
            if (!$entry->isBalanced()) {
                $issues[] = "Entry #{$entry->entry_number} is not balanced.";
            }
        }

        // Check for orphaned lines
        $orphans = LedgerLine::whereDoesntHave('entry')->count();
        if ($orphans > 0) {
            $issues[] = "Found {$orphans} orphaned ledger lines.";
        }

        return $issues;
    }

    protected function generateEntryNumber(Carbon $date): string
    {
        $prefix = $date->format('Ymd');
        $last = LedgerEntry::where('entry_number', 'like', "{$prefix}-%")
            ->orderBy('entry_number', 'desc')
            ->value('entry_number');

        if ($last) {
            $seq = (int) substr($last, -5) + 1;
        } else {
            $seq = 1;
        }

        return "{$prefix}-" . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    protected function ensureDateNotLocked(Carbon $date): void
    {
        $hasLocked = LedgerEntry::whereDate('entry_date', $date)
            ->where('locked', true)
            ->exists();

        if ($hasLocked) {
            throw new \RuntimeException("Cannot create entries for {$date->toDateString()}. The day has been closed.");
        }
    }
}

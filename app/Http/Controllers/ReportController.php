<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\LedgerEntry;
use App\Models\LedgerLine;
use App\Models\Player;
use App\Models\Promotion;
use App\Models\Reconciliation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function playerStatement(Request $request, Player $player)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to) : now();

        $entries = LedgerLine::where('player_id', $player->id)
            ->whereHas('entry', function ($q) use ($from, $to) {
                $q->whereDate('entry_date', '>=', $from)
                  ->whereDate('entry_date', '<=', $to);
            })
            ->with('entry')
            ->orderBy('entry.entry_date')
            ->paginate(50);

        $balance = $player->balance();

        if ($request->csv) {
            return $this->csvResponse($entries->get(), [
                'Date', 'Entry #', 'Description', 'Debit', 'Credit'
            ], function ($row) {
                return [
                    $row->entry?->entry_date?->format('Y-m-d'),
                    $row->entry?->entry_number,
                    $row->entry?->description,
                    $row->debit,
                    $row->credit,
                ];
            }, "statement-{$player->name}.csv");
        }

        return view('reports.player-statement', compact('player', 'entries', 'balance', 'from', 'to'));
    }

    public function dailyLedger(Request $request, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();

        $entries = LedgerEntry::with('lines')
            ->whereDate('entry_date', $date)
            ->latest()
            ->get();

        $totalDebit = $entries->sum(fn($e) => $e->lines->sum('debit'));
        $totalCredit = $entries->sum(fn($e) => $e->lines->sum('credit'));

        if ($request->csv) {
            return $this->csvResponse($entries, [
                'Entry #', 'Type', 'Description', 'Debit', 'Credit', 'Locked'
            ], function ($row) use ($date) {
                return [
                    $row->entry_number,
                    $row->type->value,
                    $row->description,
                    $row->lines->sum('debit'),
                    $row->lines->sum('credit'),
                    $row->locked ? 'Yes' : 'No',
                ];
            }, "daily-ledger-{$date->format('Y-m-d')}.csv");
        }

        return view('reports.daily-ledger', compact('date', 'entries', 'totalDebit', 'totalCredit'));
    }

    public function dailyClose(Request $request, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();

        $entries = LedgerEntry::with('lines')
            ->whereDate('entry_date', $date)
            ->latest()
            ->get();

        $entryCount = $entries->count();
        $totalDebit = $entries->sum(fn($e) => $e->lines->sum('debit'));
        $totalCredit = $entries->sum(fn($e) => $e->lines->sum('credit'));
        $isLocked = $entries->every(fn($e) => $e->locked);
        $reconciliation = Reconciliation::whereDate('reconciliation_date', $date)->first();

        if ($request->csv) {
            return $this->csvResponse($entries, [
                'Entry #', 'Type', 'Debit', 'Credit', 'Locked'
            ], function ($row) {
                return [
                    $row->entry_number,
                    $row->type->value,
                    $row->lines->sum('debit'),
                    $row->lines->sum('credit'),
                    $row->locked ? 'Yes' : 'No',
                ];
            }, "daily-close-{$date->format('Y-m-d')}.csv");
        }

        return view('reports.daily-close', compact(
            'date', 'entries', 'entryCount', 'totalDebit', 'totalCredit', 'isLocked', 'reconciliation'
        ));
    }

    public function promoLiability(Request $request)
    {
        $promotions = Promotion::where('active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->get();

        $totalCap = $promotions->sum('cap');
        $totalClaimed = $promotions->sum('claimed_liability');
        $totalRemaining = $promotions->sum(fn($p) => $p->cap ? max(0, $p->cap - $p->claimed_liability) : 0);

        if ($request->csv) {
            return $this->csvResponse($promotions, [
                'Promotion', 'Type', 'Value', 'Cap', 'Claimed', 'Remaining', 'Period'
            ], function ($row) {
                return [
                    $row->name,
                    $row->type->value,
                    $row->value,
                    $row->cap ?? 'Unlimited',
                    $row->claimed_liability,
                    $row->cap ? max(0, $row->cap - $row->claimed_liability) : 'N/A',
                    "{$row->starts_at->format('Y-m-d')} - {$row->ends_at?->format('Y-m-d') ?? '∞'}",
                ];
            }, "promo-liability.csv");
        }

        return view('reports.promo-liability', compact('promotions', 'totalCap', 'totalClaimed', 'totalRemaining'));
    }

    public function agentExposure(Request $request, $agentId = null)
    {
        $allAgents = Agent::where('active', true)->get();

        if ($agentId) {
            $agents = Agent::where('id', $agentId)
                ->with(['players.riskFlags' => fn($q) => $q->where('status', 'open')])
                ->get();
        } elseif ($request->filled('agent')) {
            $agents = Agent::where('id', $request->agent)
                ->with(['players.riskFlags' => fn($q) => $q->where('status', 'open')])
                ->get();
        } else {
            $agents = Agent::where('active', true)
                ->with(['players.riskFlags' => fn($q) => $q->where('status', 'open')])
                ->get();
        }

        if ($request->csv) {
            $rows = collect();
            foreach ($agents as $agent) {
                foreach ($agent->players as $player) {
                    $rows->push([
                        'agent' => $agent->name,
                        'player' => $player->name,
                        'status' => $player->status->value,
                        'balance' => $player->balance(),
                        'risk_flags' => $player->riskFlags->count(),
                    ]);
                }
            }
            return $this->csvResponse($rows, [
                'Agent', 'Player', 'Status', 'Balance', 'Risk Flags'
            ], fn($row) => $row, "agent-exposure.csv");
        }

        return view('reports.agent-exposure', compact('agents', 'allAgents'));
    }

    public function openDisputes(Request $request)
    {
        $entries = LedgerEntry::where('type', \App\Enums\TransactionType::DisputeHold)
            ->with(['creator', 'lines.player'])
            ->latest()
            ->paginate(25);

        return view('reports.open-disputes', compact('entries'));
    }

    public function ledgerExceptions(Request $request)
    {
        $services = app(\App\Services\LedgerService::class);
        $issues = $services->auditLedger();

        return view('reports.ledger-exceptions', compact('issues'));
    }

    public function activityByPlatform(Request $request)
    {
        $platforms = DB::table('player_platform_accounts')
            ->select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->get();

        return view('reports.activity-by-platform', compact('platforms'));
    }

    protected function csvResponse($data, array $headers, callable $mapper, string $filename)
    {
        $callback = function () use ($data, $headers, $mapper) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($data as $row) {
                fputcsv($handle, $mapper($row));
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

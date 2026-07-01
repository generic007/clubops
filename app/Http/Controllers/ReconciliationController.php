<?php

namespace App\Http\Controllers;

use App\Models\Reconciliation;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller
{
    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    public function index()
    {
        $reconciliations = Reconciliation::with(['creator'])->latest()->paginate(25);
        return view('reconciliations.index', compact('reconciliations'));
    }

    public function create()
    {
        return view('reconciliations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reconciliation_date' => 'required|date|before_or_equal:today',
            'platform_total' => 'required|numeric|min:0',
            'platform' => 'nullable|string|max:50',
            'screenshot' => 'nullable|image|max:10240',
            'notes' => 'nullable|string|max:1000',
        ]);

        $reconciliation = $this->ledger->reconcile(
            \Carbon\Carbon::parse($validated['reconciliation_date']),
            $validated['platform_total'],
            $request->user(),
        );

        if ($request->hasFile('screenshot')) {
            $reconciliation->update(['notes' => $validated['notes'] ?? '']);
            // Handle screenshot storage if needed
        }

        $status = $reconciliation->hasVariance() ? 'Variance detected.' : 'Matched.';

        return redirect()->route('reconciliations.show', $reconciliation)
            ->with('success', "Reconciliation created. {$status}");
    }

    public function show(Reconciliation $reconciliation)
    {
        $reconciliation->load(['items.entry', 'creator', 'locker']);
        return view('reconciliations.show', compact('reconciliation'));
    }

    public function lock(Request $request, Reconciliation $reconciliation)
    {
        if ($reconciliation->isLocked()) {
            return back()->with('error', 'This reconciliation is already locked.');
        }

        if ($reconciliation->hasVariance()) {
            return back()->with('error', 'Cannot lock a reconciliation with variance.');
        }

        DB::transaction(function () use ($reconciliation, $request) {
            $this->ledger->dailyClose(
                $reconciliation->reconciliation_date,
                $request->user(),
            );

            $reconciliation->update([
                'locked_by' => $request->user()->id,
                'locked_at' => now(),
                'status' => 'locked',
            ]);
        });

        return redirect()->route('reconciliations.show', $reconciliation)
            ->with('success', "Day closed for {$reconciliation->reconciliation_date->format('M d, Y')}.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\LedgerService;
use App\Models\LedgerEntry;
use App\Models\LedgerAccount;
use App\Models\Player;
use App\Http\Requests\StoreLedgerEntryRequest;
use Illuminate\Http\Request;

class LedgerEntryController extends Controller
{
    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    public function index(Request $request)
    {
        $query = LedgerEntry::with(['creator', 'lines.account']);

        if ($request->filled('date')) {
            $query->whereDate('entry_date', $request->date);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('player_id')) {
            $query->whereHas('lines', fn($q) => $q->where('player_id', $request->player_id));
        }

        $entries = $query->latest()->paginate(25);
        $accounts = LedgerAccount::active()->get();
        $types = \App\Enums\TransactionType::cases();

        return view('ledger.entries.index', compact('entries', 'accounts', 'types'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', LedgerEntry::class);
        $accounts = LedgerAccount::active()->get();
        $players = Player::active()->get();
        $types = \App\Enums\TransactionType::cases();

        return view('ledger.entries.create', compact('accounts', 'players', 'types'));
    }

    public function store(StoreLedgerEntryRequest $request)
    {
        $this->authorize('create', LedgerEntry::class);

        $entry = $this->ledger->createEntry(
            $request->type,
            $request->description,
            $request->user(),
            $request->lines,
            $request->source_type,
            $request->source_id,
            $request->reference,
            $request->filled('entry_date') ? \Carbon\Carbon::parse($request->entry_date) : null,
        );

        return redirect()->route('ledger.entries.show', $entry)
            ->with('success', "Entry #{$entry->entry_number} created.");
    }

    public function show(LedgerEntry $entry)
    {
        $entry->load(['lines.account', 'lines.player', 'creator', 'reversedEntry']);
        return view('ledger.entries.show', compact('entry'));
    }

    public function void(Request $request, LedgerEntry $entry)
    {
        $this->authorize('void', $entry);

        $request->validate(['reason' => 'required|string|max:500']);

        $reversal = $this->ledger->voidEntry($entry, $request->user(), $request->reason);

        return redirect()->route('ledger.entries.show', $reversal)
            ->with('success', "Entry #{$entry->entry_number} voided. Reversal #{$reversal->entry_number} created.");
    }
}

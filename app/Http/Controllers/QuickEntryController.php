<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Agent;
use App\Models\LedgerEntry;
use App\Models\Game;
use App\Enums\TransactionType;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Quick-record buy-in and cash-out from the dashboard (2 clicks).
 */
class QuickEntryController extends Controller
{
    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    /**
     * Record a buy-in for a player.
     *
     * Accounting:
     *   Debit  → Operating Cash (1)   — house receives cash from player
     *   Credit → Player Funds (2)     — player receives chips (liability)
     */
    public function buyIn(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'amount'    => 'required|numeric|min:0.01|max:100000',
            'game_id'   => 'nullable|exists:games,id',
            'notes'     => 'nullable|string|max:255',
        ]);

        $agent = $request->user();
        $player = Player::findOrFail($request->player_id);
        $amount = (float) $request->amount;

        $entry = $this->ledger->createEntry(
            type: TransactionType::BuyIn->value,
            description: $request->notes ?? 'Buy-in',
            createdBy: $agent,
            lines: [
                [
                    'account_id' => 1, // Operating Cash
                    'player_id'  => null,
                    'debit'      => $amount,
                    'credit'     => 0,
                ],
                [
                    'account_id' => 2, // Player Funds (liability)
                    'player_id'  => $player->id,
                    'debit'      => 0,
                    'credit'     => $amount,
                ],
            ],
            sourceType: 'player',
            sourceId: $player->id,
            reference: $request->game_id ? "GAME-{$request->game_id}" : null,
        );

        // Update last played
        $player->update(['last_played_at' => now()]);

        return redirect()->back()->with('success', "Buy-in of \${$request->amount} recorded for {$player->name}.");
    }

    /**
     * Record a cash-out for a player.
     *
     * Accounting:
     *   Debit  → Player Funds (2)     — player gives back chips (liability reduced)
     *   Credit → Operating Cash (1)   — house pays cash to player
     */
    public function cashOut(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'amount'    => 'required|numeric|min:0.01|max:100000',
            'game_id'   => 'nullable|exists:games,id',
            'notes'     => 'nullable|string|max:255',
        ]);

        $agent = $request->user();
        $player = Player::findOrFail($request->player_id);
        $amount = (float) $request->amount;

        $entry = $this->ledger->createEntry(
            type: TransactionType::CashOut->value,
            description: $request->notes ?? 'Cash-out',
            createdBy: $agent,
            lines: [
                [
                    'account_id' => 2, // Player Funds (liability)
                    'player_id'  => $player->id,
                    'debit'      => $amount,
                    'credit'     => 0,
                ],
                [
                    'account_id' => 1, // Operating Cash
                    'player_id'  => null,
                    'debit'      => 0,
                    'credit'     => $amount,
                ],
            ],
            sourceType: 'player',
            sourceId: $player->id,
            reference: $request->game_id ? "GAME-{$request->game_id}" : null,
        );

        return redirect()->back()->with('success', "Cash-out of \${$request->amount} recorded for {$player->name}.");
    }

    /**
     * AJAX search for players by name (for quick-entry typeahead).
     */
    public function searchPlayers(Request $request)
    {
        $agent = $request->user();
        $query = $request->get('q', '');

        $players = Player::where('club_id', $agent->club_id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($players);
    }
}

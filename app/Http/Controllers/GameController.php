<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Agent;
use App\Enums\AgentRole;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function index(Request $request)
    {
        $query = Game::with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $games = $query->latest('scheduled_at')->paginate(25);
        $statuses = ['scheduled', 'running', 'completed', 'cancelled'];
        $platforms = Game::distinct()->pluck('platform');
        $types = Game::distinct()->pluck('type');

        return view('games.index', compact('games', 'statuses', 'platforms', 'types'));
    }

    public function create()
    {
        $agents = Agent::where('active', true)->get();
        return view('games.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'stakes' => 'required|string|max:50',
            'platform' => 'required|string|max:50',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $game = Game::create([
            ...$validated,
            'status' => 'scheduled',
            'created_by' => $request->user()->id,
        ]);

        $this->audit->log(
            $request->user(),
            'game_created',
            $game,
            null,
            ['name' => $game->name, 'type' => $game->type, 'stakes' => $game->stakes],
            "Game created: {$game->name}"
        );

        return redirect()->route('games.show', $game)
            ->with('success', "Game '{$game->name}' created.");
    }

    public function show(Game $game)
    {
        $game->load(['sessions.player', 'creator']);
        $totalBuyins = $game->sessions->sum('buy_in');
        $totalCashouts = $game->sessions->sum('cash_out');
        $playerCount = $game->sessions->count();
        return view('games.show', compact('game', 'totalBuyins', 'totalCashouts', 'playerCount'));
    }

    public function edit(Game $game)
    {
        $agents = Agent::where('active', true)->get();
        return view('games.edit', compact('game', 'agents'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'stakes' => 'required|string|max:50',
            'platform' => 'required|string|max:50',
            'status' => 'required|in:scheduled,running,completed,cancelled',
            'scheduled_at' => 'required|date',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after_or_equal:started_at',
            'notes' => 'nullable|string|max:1000',
        ]);

        $game->update($validated);

        $this->audit->log(
            $request->user(),
            'game_updated',
            $game,
            null,
            ['name' => $game->name, 'status' => $game->status],
            "Game updated: {$game->name}"
        );

        return redirect()->route('games.show', $game)
            ->with('success', "Game '{$game->name}' updated.");
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')
            ->with('success', "Game '{$game->name}' deleted.");
    }

    public function startSession(Request $request, Game $game)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'buy_in' => 'required|numeric|min:0|max:999999',
        ]);

        $session = $game->sessions()->create([
            'player_id' => $validated['player_id'],
            'buy_in' => $validated['buy_in'],
        ]);

        if ($game->status === 'scheduled') {
            $game->update(['status' => 'running', 'started_at' => now()]);
        }

        return back()->with('success', 'Session started.');
    }

    public function endSession(Request $request, \App\Models\GameSession $session)
    {
        $validated = $request->validate([
            'cash_out' => 'required|numeric|min:0|max:999999',
        ]);

        $session->update([
            'cash_out' => $validated['cash_out'],
            'profit_loss' => $validated['cash_out'] - $session->buy_in,
            'duration_minutes' => $session->created_at->diffInMinutes(now()),
        ]);

        return back()->with('success', 'Session ended.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tag;
use App\Services\PlayerCrmService;
use App\Http\Requests\StorePlayerRequest;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected PlayerCrmService $crm;

    public function __construct(PlayerCrmService $crm)
    {
        $this->crm = $crm;
        $this->authorizeResource(Player::class, 'player');
    }

    public function index(Request $request)
    {
        $query = Player::query()->with(['agent', 'tags', 'platformAccounts']);

        if ($request->user()->isAgent()) {
            $query->where('agent_id', $request->user()->id)
                ->orWhere('assigned_admin_id', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('name', $request->tag));
        }

        $players = $query->latest()->paginate(20);
        $tags = Tag::all();
        $statuses = \App\Enums\PlayerStatus::cases();

        return view('players.index', compact('players', 'tags', 'statuses'));
    }

    public function create()
    {
        $agents = \App\Models\Agent::where('active', true)->get();
        $tags = Tag::all();
        return view('players.create', compact('agents', 'tags'));
    }

    public function store(StorePlayerRequest $request)
    {
        $player = Player::create($request->validated());

        if ($request->filled('platform_accounts')) {
            $this->crm->syncPlatformAccounts($player, $request->platform_accounts);
        }

        if ($request->filled('tags')) {
            $player->tags()->sync($request->tags);
        }

        return redirect()->route('players.index')
            ->with('success', "Player '{$player->name}' created.");
    }

    public function show(Player $player)
    {
        $player->load(['platformAccounts', 'tags', 'notes.agent', 'agent',
            'riskFlags', 'promoRedemptions.promotion', 'tickets',
            'compliance', 'exclusions']);
        $balance = $player->balance();
        $lifetimeVolume = $player->lifetimeVolume();
        $lifetimePnl = $player->lifetimeProfitLoss();
        return view('players.show', compact('player', 'balance', 'lifetimeVolume', 'lifetimePnl'));
    }

    public function edit(Player $player)
    {
        $this->authorize('update', $player);
        $agents = \App\Models\Agent::where('active', true)->get();
        $tags = Tag::all();
        $player->load('platformAccounts', 'tags');
        return view('players.edit', compact('player', 'agents', 'tags'));
    }

    public function update(StorePlayerRequest $request, Player $player)
    {
        $this->authorize('update', $player);
        $player->update($request->validated());

        if ($request->filled('platform_accounts')) {
            $this->crm->syncPlatformAccounts($player, $request->platform_accounts);
        }

        $player->tags()->sync($request->tags ?? []);

        return redirect()->route('players.show', $player)
            ->with('success', "Player '{$player->name}' updated.");
    }

    public function destroy(Player $player)
    {
        $this->authorize('delete', $player);
        $player->delete();
        return redirect()->route('players.index')
            ->with('success', "Player '{$player->name}' deleted.");
    }

    public function markContacted(Player $player, Request $request)
    {
        $this->authorize('update', $player);
        $player->update(['last_contacted_at' => now()]);
        return back()->with('success', 'Contact time updated.');
    }

    public function addTag(Request $request, Player $player)
    {
        $this->authorize('update', $player);
        $tag = Tag::firstOrCreate(['name' => $request->tag]);
        $player->tags()->syncWithoutDetaching($tag->id);
        return back()->with('success', "Tag '{$tag->name}' added.");
    }

    public function removeTag(Player $player, Tag $tag)
    {
        $this->authorize('update', $player);
        $player->tags()->detach($tag->id);
        return back()->with('success', 'Tag removed.');
    }
}

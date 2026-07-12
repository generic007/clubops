<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Player;
use App\Services\LedgerService;
use App\Models\PromotionRedemption;
use App\Enums\TransactionType;
use App\Traits\ExportsCsv;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    use ExportsCsv;
    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    public function index()
    {
        $promotions = Promotion::withCount('redemptions')
            ->latest()
            ->paginate(25);
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_column(\App\Enums\PromoType::cases(), 'value')),
            'description' => 'nullable|string',
            'value' => 'required|numeric|min:0',
            'cap' => 'nullable|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'active' => 'boolean',
            'terms' => 'nullable|string',
        ]);

        $promotion = Promotion::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'value' => $validated['value'],
            'cap' => $validated['cap'] ?: null,
            'total_liability' => $validated['cap'] ?: 0,
            'claimed_liability' => 0,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'active' => $request->boolean('active', true),
            'terms' => $validated['terms'] ?? null,
        ]);

        return redirect()->route('promotions.show', $promotion)
            ->with('success', "Promotion '{$promotion->name}' created.");
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['redemptions.player', 'redemptions.ledgerEntry']);
        return view('promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_column(\App\Enums\PromoType::cases(), 'value')),
            'description' => 'nullable|string',
            'value' => 'required|numeric|min:0',
            'cap' => 'nullable|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'active' => 'boolean',
            'terms' => 'nullable|string',
        ]);

        $promotion->update($validated + [
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('promotions.show', $promotion)
            ->with('success', "Promotion '{$promotion->name}' updated.");
    }

    public function destroy(Promotion $promotion, Request $request)
    {
        $promotion->update(['active' => false]);
        return redirect()->route('promotions.index')
            ->with('success', "Promotion '{$promotion->name}' deactivated.");
    }

    public function redeem(Request $request, Promotion $promotion, Player $player)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($promotion->cap ? $promotion->remainingCap() : 999999),
            'notes' => 'nullable|string|max:500',
        ]);

        if (!$promotion->isActive()) {
            return back()->with('error', 'This promotion is not active.');
        }

        if ($promotion->cap && $promotion->claimed_liability + $request->amount > $promotion->cap) {
            return back()->with('error', 'Redemption would exceed the promotion cap.');
        }

        $entry = $this->ledger->createEntry(
            TransactionType::PromoCredit->value,
            "Promo redemption: {$promotion->name} for {$player->name}",
            $request->user(),
            [
                [
                    'account_id' => 1, // Promo liability account
                    'player_id' => $player->id,
                    'debit' => 0,
                    'credit' => $request->amount,
                ],
                [
                    'account_id' => 2, // Player balance account
                    'player_id' => $player->id,
                    'debit' => $request->amount,
                    'credit' => 0,
                ],
            ],
            'player',
            $player->id,
            "PROMO-{$promotion->id}",
        );

        $redemption = PromotionRedemption::create([
            'promotion_id' => $promotion->id,
            'player_id' => $player->id,
            'ledger_entry_id' => $entry->id,
            'amount' => $request->amount,
            'status' => 'completed',
            'notes' => $request->notes,
            'claimed_at' => now(),
        ]);

        $promotion->increment('claimed_liability', $request->amount);

        return redirect()->route('promotions.show', $promotion)
            ->with('success', "{$request->amount} redeemed for {$player->name}.");
    }

    public function export()
    {
        $promotions = Promotion::withCount('redemptions')->latest()->get();

        return $this->exportCsv($promotions, [
            'Name', 'Type', 'Value', 'Cap', 'Claimed', 'Starts', 'Ends', 'Active', 'Redemptions',
        ], function ($promo) {
            return [
                $promo->name,
                $promo->type->value,
                $promo->value,
                $promo->cap ?? 'Uncapped',
                $promo->claimed_liability ?? 0,
                $promo->starts_at->format('Y-m-d'),
                $promo->ends_at?->format('Y-m-d') ?? '∞',
                $promo->active ? 'Yes' : 'No',
                $promo->redemptions_count,
            ];
        }, 'promotions-' . now()->format('Y-m-d') . '.csv');
    }
}

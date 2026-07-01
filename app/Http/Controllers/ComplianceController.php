<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Exclusion;
use App\Models\ComplianceProfile;
use App\Services\AuditService;
use App\Enums\PlayerStatus;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function index(Request $request)
    {
        $query = Player::with(['compliance', 'exclusions']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('id_status')) {
            $status = $request->id_status;
            if ($status === 'verified') {
                $query->whereHas('compliance', fn($q) => $q->where('id_verification_status', 'verified'));
            } elseif ($status === 'pending') {
                $query->whereHas('compliance', fn($q) => $q->where('id_verification_status', 'pending'));
            } elseif ($status === 'unverified') {
                $query->whereDoesntHave('compliance')
                      ->orWhereHas('compliance', fn($q) => $q->whereNull('id_verification_status'));
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'excluded') {
                $query->where('status', PlayerStatus::Excluded);
            } elseif ($request->status === 'complete') {
                $query->where('compliance_complete', true);
            } elseif ($request->status === 'pending') {
                $query->where('compliance_complete', false);
            }
        }

        $players = $query->latest()->paginate(25);

        $completeCount = Player::where('compliance_complete', true)->count();
        $pendingCount = Player::where('compliance_complete', false)->count();
        $excludedCount = Player::where('status', PlayerStatus::Excluded)->count();
        $idVerifiedCount = ComplianceProfile::where('id_verification_status', 'verified')->count();

        return view('compliance.index', compact(
            'players', 'completeCount', 'pendingCount', 'excludedCount', 'idVerifiedCount'
        ));
    }

    public function show(Player $player)
    {
        $player->load(['compliance', 'exclusions.createdBy']);
        $profile = $player->compliance;
        return view('compliance.show', compact('player', 'profile'));
    }

    public function excludePlayer(Request $request, Player $player)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:temporary,permanent,self_excluded,regulatory',
            'reason' => 'required|string|max:1000',
            'ends_at' => 'nullable|date|after:today',
        ]);

        $exclusion = Exclusion::create([
            'player_id' => $player->id,
            'type' => $validated['type'],
            'starts_at' => now(),
            'ends_at' => $validated['ends_at'] ?? null,
            'reason' => $validated['reason'],
            'created_by' => $request->user()->id,
        ]);

        $player->update(['status' => PlayerStatus::Excluded]);

        $this->audit->logSensitiveAction(
            $request->user(),
            'player_excluded',
            $exclusion,
            ['player_id' => $player->id, 'type' => $validated['type'], 'reason' => $validated['reason']]
        );

        return redirect()->route('compliance.show', $player)
            ->with('success', "Player '{$player->name}' has been excluded.");
    }

    public function reinstatePlayer(Request $request, Player $player)
    {
        $activeExclusion = $player->exclusions()
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->first();

        if ($activeExclusion) {
            $activeExclusion->update(['ends_at' => now()]);
        }

        $player->update(['status' => PlayerStatus::Active]);

        $this->audit->log(
            $request->user(),
            'player_reinstated',
            $player,
            ['status' => PlayerStatus::Excluded->value],
            ['status' => PlayerStatus::Active->value],
            "Reinstated player {$player->name}"
        );

        return redirect()->route('compliance.show', $player)
            ->with('success', "Player '{$player->name}' reinstated.");
    }
}

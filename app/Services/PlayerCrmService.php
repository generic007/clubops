<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Player;
use App\Models\PlayerNote;
use App\Models\PlayerPlatformAccount;
use App\Models\RiskFlag;
use App\Enums\PlayerStatus;
use App\Enums\RiskLevel;

class PlayerCrmService
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function addNote(Player $player, Agent $agent, string $note, ?string $category = 'general'): PlayerNote
    {
        $playerNote = PlayerNote::create([
            'player_id' => $player->id,
            'agent_id' => $agent->id,
            'note' => $note,
            'category' => $category,
        ]);

        $this->audit->log(
            $agent,
            'player_note_created',
            $playerNote,
            null,
            ['category' => $category],
            "Note added to player #{$player->id}"
        );

        return $playerNote;
    }

    public function updateStatus(Player $player, PlayerStatus $status, Agent $agent, ?string $reason = null): void
    {
        $old = $player->status;
        $player->update(['status' => $status]);

        $this->audit->log(
            $agent,
            'player_status_changed',
            $player,
            ['status' => $old->value],
            ['status' => $status->value, 'reason' => $reason],
            "Player #{$player->id} status: {$old->value} → {$status->value}"
        );
    }

    public function syncPlatformAccounts(Player $player, array $accounts): void
    {
        $player->platformAccounts()->delete();
        foreach ($accounts as $acct) {
            PlayerPlatformAccount::create([
                'player_id' => $player->id,
                'platform' => $acct['platform'],
                'username' => $acct['username'],
                'user_id' => $acct['user_id'] ?? null,
                'verified' => $acct['verified'] ?? false,
            ]);
        }
    }

    public function flagRisk(
        Player $player,
        Agent $agent,
        string $type,
        string $description,
        string $severity = 'medium',
    ): RiskFlag {
        $flag = RiskFlag::create([
            'player_id' => $player->id,
            'raised_by' => $agent->id,
            'type' => $type,
            'description' => $description,
            'severity' => $severity,
        ]);

        $player->update(['risk_status' => RiskLevel::tryFrom($severity) ?? RiskLevel::Medium]);

        $this->audit->logSensitiveAction(
            $agent,
            'risk_flag_raised',
            $flag,
            ['type' => $type, 'severity' => $severity]
        );

        return $flag;
    }
}

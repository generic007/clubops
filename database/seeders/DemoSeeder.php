<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Player;
use App\Models\PlayerNote;
use App\Models\PlayerPlatformAccount;
use App\Models\Tag;
use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use App\Models\LedgerLine;
use App\Models\SupportTicket;
use App\Models\TicketComment;
use App\Models\Promotion;
use App\Models\PromotionRedemption;
use App\Models\Game;
use App\Models\GameSession;
use App\Enums\AgentRole;
use App\Enums\PlayerStatus;
use App\Enums\RiskLevel;
use App\Enums\TransactionType;
use App\Enums\TicketType;
use App\Enums\TicketStatus;
use App\Enums\PromoType;
use App\Services\LedgerService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Agent::where('email', 'owner@clubops.local')->first();
        $manager = Agent::where('email', 'manager@clubops.local')->first();

        if (!$owner) {
            $this->call(AdminSeeder::class);
            $owner = Agent::where('email', 'owner@clubops.local')->first();
            $manager = Agent::where('email', 'manager@clubops.local')->first();
        }

        // Create additional agents
        $agent1 = Agent::create([
            'name' => 'Alex Agent',
            'email' => 'alex@clubops.local',
            'password' => Hash::make('demo1234'),
            'role' => AgentRole::Agent,
            'phone' => '555-0101',
            'active' => true,
            'created_by' => $owner->id,
        ]);

        $agent2 = Agent::create([
            'name' => 'Sam Support',
            'email' => 'sam@clubops.local',
            'password' => Hash::make('demo1234'),
            'role' => AgentRole::Support,
            'phone' => '555-0102',
            'active' => true,
            'created_by' => $owner->id,
        ]);

        $this->command->info('Agents created.');

        // Create tags
        $tags = [];
        foreach (['Whale', 'Reg', 'Fish', 'Nit', 'Action', 'Rec', 'Pro'] as $tagName) {
            $tags[$tagName] = Tag::create(['name' => $tagName, 'color' => '#' . substr(md5($tagName), 0, 6)]);
        }
        $this->command->info('Tags created.');

        // Create players with realistic profiles
        $playerData = [
            ['name' => 'Mike "The Grinder" Chen', 'preferred_name' => 'Mike', 'phone' => '555-1001', 'email' => 'mike@example.com', 'status' => PlayerStatus::Vip, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'Referral: John D.', 'preferred_game' => 'PLO', 'preferred_stakes' => '5/5/10', 'tags' => ['Whale', 'Action']],
            ['name' => 'Sarah Johnson', 'preferred_name' => 'Sarah', 'phone' => '555-1002', 'email' => 'sarah@example.com', 'status' => PlayerStatus::Active, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'ClubGG', 'preferred_game' => 'NLH', 'preferred_stakes' => '2/5', 'tags' => ['Reg']],
            ['name' => 'Tom Williams', 'preferred_name' => 'Tom', 'phone' => '555-1003', 'email' => 'tom@example.com', 'status' => PlayerStatus::Active, 'risk_status' => RiskLevel::Medium, 'agent_id' => $agent1->id, 'referral_source' => 'Social media', 'preferred_game' => 'PLO', 'preferred_stakes' => '5/5', 'tags' => ['Fish', 'Action']],
            ['name' => 'Emily Davis', 'preferred_name' => 'Em', 'phone' => '555-1004', 'email' => 'emily@example.com', 'status' => PlayerStatus::Lead, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent2->id, 'referral_source' => 'Friend of Mike', 'preferred_game' => 'NLH', 'preferred_stakes' => '1/2', 'tags' => ['Rec']],
            ['name' => 'David Rodriguez', 'preferred_name' => 'Dave', 'phone' => '555-1005', 'email' => 'dave@example.com', 'status' => PlayerStatus::Active, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'ClubGG', 'preferred_game' => 'PLO', 'preferred_stakes' => '5/5/10', 'tags' => ['Pro', 'Nit']],
            ['name' => 'Lisa Park', 'preferred_name' => 'Lisa', 'phone' => '555-1006', 'email' => 'lisa@example.com', 'status' => PlayerStatus::Active, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent2->id, 'referral_source' => 'PPPoker', 'preferred_game' => 'Mixed', 'preferred_stakes' => '5/5', 'tags' => ['Reg']],
            ['name' => 'James Thompson', 'preferred_name' => 'JT', 'phone' => '555-1007', 'email' => 'james@example.com', 'status' => PlayerStatus::Inactive, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'PokerBros', 'preferred_game' => 'NLH', 'preferred_stakes' => '2/5', 'tags' => ['Nit']],
            ['name' => 'Rachel Kim', 'preferred_name' => 'Rae', 'phone' => '555-1008', 'email' => 'rachel@example.com', 'status' => PlayerStatus::Vip, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'Referral: Mike', 'preferred_game' => 'PLO', 'preferred_stakes' => '5/5/10', 'tags' => ['Whale', 'Action']],
            ['name' => 'Chris Brown', 'preferred_name' => 'Chris', 'phone' => '555-1009', 'email' => 'chris@example.com', 'status' => PlayerStatus::Suspended, 'risk_status' => RiskLevel::High, 'agent_id' => $agent2->id, 'referral_source' => 'PPPoker', 'preferred_game' => 'NLH', 'preferred_stakes' => '5/10', 'tags' => ['Fish']],
            ['name' => 'Alex Martin', 'preferred_name' => 'Alex', 'phone' => '555-1010', 'email' => 'alex@example.com', 'status' => PlayerStatus::Lead, 'risk_status' => RiskLevel::Low, 'agent_id' => $agent1->id, 'referral_source' => 'Website signup', 'preferred_game' => 'PLO', 'preferred_stakes' => '2/5', 'tags' => ['Rec']],
        ];

        $players = [];
        foreach ($playerData as $data) {
            $tagNames = $data['tags'] ?? [];
            unset($data['tags']);

            $player = Player::create($data);
            $player->tags()->attach(array_map(fn($t) => $tags[$t]->id, $tagNames));
            $player->platformAccounts()->createMany([
                ['platform' => 'ClubGG', 'username' => strtolower(str_replace(' ', '_', $data['name'])), 'verified' => true],
                ['platform' => 'PPPoker', 'username' => str_replace(' ', '', $data['name']), 'verified' => false],
            ]);
            $players[$player->id] = $player;

            PlayerNote::create([
                'player_id' => $player->id,
                'agent_id' => $agent1->id,
                'note' => "Initial contact made. Player seems interested in {$data['preferred_game']} {$data['preferred_stakes']} games.",
                'category' => 'general',
            ]);
        }
        $this->command->info('10 players created with platform accounts, tags, and notes.');

        // Create ledger entries for some players
        $ledgerService = app(LedgerService::class);
        $promoAccount = LedgerAccount::where('code', '4000')->first();
        $liabilityAccount = LedgerAccount::where('code', '2000')->first();
        $incomeAccount = LedgerAccount::where('code', '3000')->first();

        foreach ([1, 2, 3, 6, 8] as $playerId) {
            $player = $players[$playerId] ?? null;
            if (!$player) continue;

            $ledgerService->createEntry(
                TransactionType::ManualEntry->value,
                "Initial credit for {$player->name}",
                $owner,
                [
                    ['account_id' => $promoAccount->id, 'player_id' => $player->id, 'debit' => 0, 'credit' => 500],
                    ['account_id' => $liabilityAccount->id, 'player_id' => null, 'debit' => 500, 'credit' => 0],
                ],
                'player', $player->id,
                null, Carbon::yesterday(),
            );

            $ledgerService->createEntry(
                TransactionType::ManualEntry->value,
                "Platform adjustment for {$player->name}",
                $owner,
                [
                    ['account_id' => $promoAccount->id, 'player_id' => $player->id, 'debit' => 200, 'credit' => 0],
                    ['account_id' => $incomeAccount->id, 'player_id' => null, 'debit' => 0, 'credit' => 200],
                ],
                'player', $player->id,
                null, Carbon::today(),
            );
        }
        $this->command->info('Ledger entries created for 5 players.');

        // Create promotions
        $welcomePromo = Promotion::create([
            'name' => 'Welcome Bonus 2026',
            'type' => PromoType::WelcomeBonus,
            'description' => '$500 welcome bonus for new players. $200 deposit required.',
            'value' => 500,
            'cap' => 10000,
            'starts_at' => Carbon::now()->subDays(30),
            'ends_at' => Carbon::now()->addMonths(6),
            'active' => true,
            'terms' => 'Must deposit $200 within 7 days of account creation. Bonus releases at $10/hour played.',
        ]);

        $referralPromo = Promotion::create([
            'name' => 'Summer Referral Bonus',
            'type' => PromoType::ReferralBonus,
            'description' => 'Refer a friend and get $200 when they play 20+ hours.',
            'value' => 200,
            'cap' => 5000,
            'starts_at' => Carbon::now()->subDays(15),
            'ends_at' => Carbon::now()->addMonths(3),
            'active' => true,
            'terms' => 'Referred player must play 20+ hours within 30 days of first login.',
        ]);

        // Redeem promo for Mike
        PromotionRedemption::create([
            'promotion_id' => $welcomePromo->id,
            'player_id' => 1,
            'amount' => 500,
            'status' => 'approved',
            'claimed_at' => Carbon::now()->subDays(2),
        ]);
        $welcomePromo->increment('claimed_liability', 500);

        $this->command->info('2 promotions + 1 redemption created.');

        // Create support tickets
        $ticket = SupportTicket::create([
            'ticket_number' => 'TKT-00001',
            'player_id' => 3,
            'assigned_to' => $agent2->id,
            'subject' => 'Missing promo credit not applied',
            'description' => "Tom signed up for the Welcome Bonus but the $500 credit hasn't appeared on his account after 48 hours.",
            'type' => TicketType::PromoIssue->value,
            'priority' => 'high',
            'status' => TicketStatus::InProgress->value,
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'author_type' => Agent::class,
            'author_id' => $agent2->id,
            'body' => 'Checking with the platform team. Will update within 24 hours.',
        ]);

        $ticket2 = SupportTicket::create([
            'ticket_number' => 'TKT-00002',
            'player_id' => 9,
            'assigned_to' => $agent2->id,
            'subject' => 'Suspected collusion in last session',
            'description' => 'Chris and another player were observed folding to each other\'s raises and showing down weak hands. Requesting review.',
            'type' => TicketType::Collusion->value,
            'priority' => 'urgent',
            'status' => TicketStatus::Open->value,
        ]);

        $this->command->info('2 support tickets + comments created.');

        // Create games
        $game1 = Game::create([
            'name' => 'Friday Night PLO',
            'type' => 'PLO',
            'stakes' => '5/5/10',
            'platform' => 'ClubGG',
            'status' => 'scheduled',
            'scheduled_at' => Carbon::now()->next(Carbon::FRIDAY)->setHour(19)->setMinute(0),
            'notes' => 'Regular Friday game. Expect 8-10 players.',
            'created_by' => $owner->id,
        ]);

        $game2 = Game::create([
            'name' => 'Saturday Mix',
            'type' => 'Mixed',
            'stakes' => '5/5',
            'platform' => 'PPPoker',
            'status' => 'scheduled',
            'scheduled_at' => Carbon::now()->next(Carbon::SATURDAY)->setHour(18)->setMinute(0),
            'notes' => 'Mix of PLO and NLH. Players voted.',
            'created_by' => $owner->id,
        ]);

        $game3 = Game::create([
            'name' => 'Wednesday NLH',
            'type' => 'NLH',
            'stakes' => '2/5',
            'platform' => 'PokerBros',
            'status' => 'completed',
            'scheduled_at' => Carbon::now()->subDays(2)->setHour(20)->setMinute(0),
            'started_at' => Carbon::now()->subDays(2)->setHour(20)->setMinute(15),
            'ended_at' => Carbon::now()->subDays(2)->setHour(23)->setMinute(45),
            'notes' => 'Good action. 7 players.',
            'created_by' => $manager->id,
        ]);

        $this->command->info('3 games created.');

        $this->command->info('');
        $this->command->info('🎲 Demo data seeded successfully!');
        $this->command->warn('Demo account passwords: demo1234 (all non-owner agents)');
        $this->command->info('Log in: owner@clubops.local / change-me');
    }
}

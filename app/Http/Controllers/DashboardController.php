<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\LedgerEntry;
use App\Models\SupportTicket;
use App\Models\Reconciliation;
use App\Models\Promotion;
use App\Enums\PlayerStatus;
use App\Enums\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();

        // KPI counts
        $activePlayers = Player::active()->count();
        $newLeads = Player::where('status', PlayerStatus::Lead)
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        $pendingOnboarding = Player::where('status', PlayerStatus::Pending)->count();
        $inactivePlayers = Player::inactive()->count();
        $highRiskPlayers = Player::highRisk()->count();
        $unresolvedTickets = SupportTicket::open()->count();
        $reconMismatches = Reconciliation::where('status', 'in_progress')->count();
        $openPromoLiability = Promotion::where('active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
            ->sum(DB::raw('total_liability - claimed_liability'));
        $dormantVips = Player::where('status', PlayerStatus::Vip)
            ->where('last_played_at', '<', now()->subDays(30))
            ->count();
        $dailyCloseStatus = LedgerEntry::whereDate('entry_date', today())
            ->where('locked', true)
            ->exists() ? 'locked' : 'open';

        // Recent activity
        $recentPlayers = Player::latest()->take(10)->get();
        $recentEntries = LedgerEntry::latest()->take(10)->get();
        $openTickets = SupportTicket::open()->latest()->take(10)->get();

        // Agent-specific players
        if ($agent->isAgent()) {
            $activePlayers = Player::active()->byAgent($agent)->count();
            $newLeads = Player::where('status', PlayerStatus::Lead)
                ->byAgent($agent)
                ->where('created_at', '>=', now()->subWeek())
                ->count();
        }

        return view('dashboard.index', compact(
            'activePlayers', 'newLeads', 'pendingOnboarding', 'unresolvedTickets',
            'reconMismatches', 'openPromoLiability', 'dormantVips', 'dailyCloseStatus',
            'recentPlayers', 'recentEntries', 'openTickets', 'agent'
        ));
    }
}

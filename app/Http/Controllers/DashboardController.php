<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Player;
use App\Models\LedgerEntry;
use App\Models\LedgerLine;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();
        $club = $agent->club;

        // KPIs
        $totalPlayers = Player::where('club_id', $club->id)->count();
        $activePlayers = Player::where('club_id', $club->id)->active()->count();
        $newThisWeek = Player::where('club_id', $club->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $recentEntries = LedgerEntry::where('club_id', $club->id)
            ->with('player')
            ->latest()
            ->limit(10)
            ->get();
        $todayVolume = LedgerLine::whereHas('entry', fn($q) =>
                $q->where('club_id', $club->id)->whereDate('created_at', today())
            )->sum('debit') + LedgerLine::whereHas('entry', fn($q) =>
                $q->where('club_id', $club->id)->whereDate('created_at', today())
            )->sum('credit');
        $openTickets = SupportTicket::where('club_id', $club->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();
        $recentPlayers = Player::where('club_id', $club->id)
            ->whereNotNull('last_played_at')
            ->latest('last_played_at')
            ->limit(5)
            ->get();

        // Active players (for quick-actions dropdown)
        $quickPlayers = Player::where('club_id', $club->id)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('dashboard.index', compact(
            'totalPlayers', 'activePlayers', 'newThisWeek',
            'recentEntries', 'todayVolume', 'openTickets',
            'recentPlayers', 'quickPlayers', 'club'
        ));
    }
}

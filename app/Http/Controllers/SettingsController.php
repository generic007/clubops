<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Club;
use App\Models\LedgerAccount;
use App\Models\Player;
use App\Models\Tag;
use App\Models\CommunicationTemplate;
use App\Enums\AgentRole;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();
        $club = $agent->club;

        $stats = [
            'total_agents' => Agent::where('club_id', $club->id)->count(),
            'total_players' => Player::where('club_id', $club->id)->count(),
            'total_accounts' => LedgerAccount::count(),
            'total_tags' => Tag::where('club_id', $club->id)->count(),
            'total_templates' => CommunicationTemplate::where('club_id', $club->id)->count(),
        ];

        $agents = Agent::where('club_id', $club->id)->withCount('players')->get();
        $ledgerAccounts = LedgerAccount::all();
        $playersWithPortal = Player::where('club_id', $club->id)->where('can_login', true)->get();

        return view('settings.index', compact('stats', 'agents', 'ledgerAccounts', 'club', 'playersWithPortal'));
    }
}

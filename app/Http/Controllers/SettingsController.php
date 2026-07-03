<?php

namespace App\Http\Controllers;

use App\Models\Agent;
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
        $stats = [
            'total_agents' => Agent::count(),
            'total_players' => Player::count(),
            'total_accounts' => LedgerAccount::count(),
            'total_tags' => Tag::count(),
            'total_templates' => CommunicationTemplate::count(),
        ];

        $agents = Agent::withCount('players')->get();
        $ledgerAccounts = LedgerAccount::all();

        return view('settings.index', compact('stats', 'agents', 'ledgerAccounts'));
    }
}

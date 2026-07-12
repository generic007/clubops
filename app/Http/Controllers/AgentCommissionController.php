<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentCommissionStructure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AgentCommissionController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();
        $club = $agent->club;

        $structures = AgentCommissionStructure::where('club_id', $club->id)
            ->with('agent')
            ->orderBy('created_at', 'desc')
            ->get();

        $agents = Agent::where('club_id', $club->id)->where('active', true)->get();

        return view('agents.commissions', compact('structures', 'agents', 'club'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type'     => 'required|in:rakeback_percentage,flat_fee_per_player,volume_tiered',
            'rate'     => 'required|numeric|min:0|max:100',
        ]);

        $agent = $request->user();

        AgentCommissionStructure::create([
            'club_id'  => $agent->club_id,
            'agent_id' => $validated['agent_id'],
            'type'     => $validated['type'],
            'rate'     => $validated['rate'] / 100, // Store as decimal
        ]);

        return redirect()->back()->with('success', 'Commission structure saved.');
    }

    public function destroy(Request $request, AgentCommissionStructure $structure)
    {
        $agent = $request->user();
        abort_unless($structure->club_id === $agent->club_id, 403);
        $structure->delete();
        return redirect()->back()->with('success', 'Commission structure removed.');
    }

    public function settle(Request $request, Agent $targetAgent)
    {
        $agent = $request->user();
        abort_unless($targetAgent->club_id === $agent->club_id, 403);

        $balance = $targetAgent->commission_balance;

        if ($balance <= 0) {
            return redirect()->back()->with('error', 'No commission to settle.');
        }

        $targetAgent->update([
            'commission_balance' => 0,
            'last_settled_at'    => now(),
        ]);

        return redirect()->back()->with('success', "Settled \${$balance} commission with {$targetAgent->name}.");
    }
}

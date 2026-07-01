<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Services\AuditService;
use App\Http\Requests\StoreAgentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
        $this->middleware('role:owner,manager')->except(['show']);
    }

    public function index()
    {
        $agents = Agent::withCount('players')->latest()->paginate(25);
        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        return view('agents.create');
    }

    public function store(StoreAgentRequest $request)
    {
        $agent = Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'active' => $request->boolean('active', true),
            'created_by' => $request->user()->id,
        ]);

        $this->audit->log(
            $request->user(),
            'agent_created',
            $agent,
            null,
            ['role' => $request->role],
            "Created agent {$agent->name}"
        );

        return redirect()->route('agents.index')
            ->with('success', "Agent '{$agent->name}' created.");
    }

    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    public function update(StoreAgentRequest $request, Agent $agent)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $data['active'] = $request->boolean('active', true);
        $agent->update($data);

        $this->audit->log(
            $request->user(),
            'agent_updated',
            $agent,
            ['role' => $agent->getOriginal('role')?->value],
            ['role' => $request->role],
            "Updated agent {$agent->name}"
        );

        return redirect()->route('agents.index')
            ->with('success', "Agent '{$agent->name}' updated.");
    }

    public function destroy(Agent $agent, Request $request)
    {
        $agent->update(['active' => false]);

        $this->audit->log(
            $request->user(),
            'agent_deactivated',
            $agent,
            null,
            null,
            "Deactivated agent {$agent->name}"
        );

        return redirect()->route('agents.index')
            ->with('success', "Agent '{$agent->name}' deactivated.");
    }
}

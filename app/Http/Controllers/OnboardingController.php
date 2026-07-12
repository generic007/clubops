<?php

namespace App\Http\Controllers;

use App\ClubOpsEdition;
use App\Models\Agent;
use App\Models\Club;
use App\Enums\AgentRole;
use App\Services\ClubEncryptionService;
use App\Services\ClubKeyRecoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class OnboardingController extends Controller
{
    /**
     * Show setup form:
     * - Free edition: always shows single-club setup
     * - Pro edition: mode selector first, then setup form
     *
     * Use /register for a no-choice, straight-to-form registration.
     */
    public function setup()
    {
        // Free edition: only one club allowed, redirect to login if already set up
        if (ClubOpsEdition::isFree() && Agent::count() > 0) {
            return redirect()->route('login');
        }

        // Pro edition with agents: additional clubs can be created (mode selector)
        if (ClubOpsEdition::isPro() && Agent::count() > 0 && !request()->has('mode')) {
            return view('auth.mode-select');
        }

        if (ClubOpsEdition::isFree()) {
            // Free edition: direct to single-club setup
            return view('auth.setup', [
                'isMultiTenant' => false,
            ]);
        }

        // Pro edition: mode selector unless already chosen
        if (!request()->has('mode')) {
            return view('auth.mode-select');
        }

        return view('auth.setup', [
            'isMultiTenant' => request('mode') === 'public',
        ]);
    }

    /**
     * Show registration form — always single-club, no mode selector.
     * This is the primary "Create Your Club" entry point.
     */
    public function register()
    {
        if (ClubOpsEdition::isFree() && Agent::count() > 0) {
            return redirect()->route('login');
        }

        return view('auth.setup', [
            'isMultiTenant' => false,
            'hideModeSelector' => true,
        ]);
    }

    /**
     * Create club + owner account.
     * Pro: multi-tenant setup with club name + team invites
     * Free: always single-club, club auto-named
     */
    public function store(Request $request)
    {
        // Free edition: only one club allowed
        if (ClubOpsEdition::isFree() && Agent::count() > 0) {
            return redirect()->route('login');
        }

        $isMultiTenant = ClubOpsEdition::isPro() && $request->boolean('multi_tenant');

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:agents,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if ($isMultiTenant) {
            $rules['club_name'] = ['required', 'string', 'max:255'];
            $rules['club_description'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        // Create the club
        $club = Club::create([
            'name'         => $isMultiTenant
                ? $validated['club_name']
                : ($validated['name'] . "'s Club"),
            'contact_email'=> $validated['email'],
            'description'  => $isMultiTenant ? ($validated['club_description'] ?? null) : null,
            'single_club'  => ClubOpsEdition::isFree(), // Free is always single-club
        ]);

        // ── Encryption: Generate club master key and encrypt with password ──
        $clubKey = ClubEncryptionService::generateClubKey();
        $encryptedBlob = ClubEncryptionService::encryptClubKey($clubKey, $validated['password']);
        $serverEncryptedBlob = ClubKeyRecoveryService::encryptForRecovery($clubKey);
        $club->update([
            'encrypted_club_key' => $encryptedBlob,
            'server_encrypted_club_key' => $serverEncryptedBlob,
        ]);
        ClubEncryptionService::storeClubKeyInSession($clubKey, $club->id);

        // Create owner agent
        $agent = Agent::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => AgentRole::Owner,
            'active'   => true,
            'club_id'  => $club->id,
        ]);

        Auth::login($agent);

        return redirect()->intended('/dashboard')
            ->with('success', ClubOpsEdition::label() . ' is ready! 🔒 All data is encrypted.');
    }
}

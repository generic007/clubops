<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Club;
use App\Models\ClubInvitation;
use App\Enums\AgentRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class InvitationController extends Controller
{
    /**
     * Show invitation management page (owner/manager only).
     */
    public function index(Request $request)
    {
        $agent = $request->user();
        $club = $agent->club;

        $invitations = $club->invitations()
            ->with('inviter')
            ->orderBy('created_at', 'desc')
            ->get();

        $agents = $club->agents()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agents.invitations', compact('club', 'invitations', 'agents'));
    }

    /**
     * Send a new invitation.
     */
    public function store(Request $request)
    {
        $agent = $request->user();
        $club = $agent->club;

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['required', 'string', 'in:manager,agent,support,accountant,auditor'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        // Check not already in club
        $existingAgent = Agent::where('club_id', $club->id)
            ->where('email', $validated['email'])
            ->exists();

        if ($existingAgent) {
            throw ValidationException::withMessages([
                'email' => 'This person is already a member of your club.',
            ]);
        }

        // Check for pending invitation
        $pending = ClubInvitation::where('club_id', $club->id)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($pending) {
            throw ValidationException::withMessages([
                'email' => 'An invitation has already been sent to this email.',
            ]);
        }

        $invitation = ClubInvitation::create([
            'club_id'    => $club->id,
            'email'      => $validated['email'],
            'role'       => $validated['role'],
            'invited_by' => $agent->id,
            'message'    => $validated['message'] ?? null,
        ]);

        // Build the accept URL
        $acceptUrl = route('invitations.accept', ['token' => $invitation->token]);

        // TODO: Send email via Mail facade when SMTP is configured
        // For now, flash the URL so the inviter can share it
        $invitationUrl = $acceptUrl;

        return redirect()->route('invitations.index')
            ->with('success', "Invitation sent to {$validated['email']}.")
            ->with('invitation_url', $invitationUrl);
    }

    /**
     * Show the accept-invitation form.
     */
    public function accept(Request $request, $token)
    {
        $invitation = ClubInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->isExpired()) {
            return view('auth.invitation-expired', compact('invitation'));
        }

        // If already registered, link them
        $existingAgent = Agent::where('email', $invitation->email)->first();

        if ($existingAgent) {
            // Link to club
            $existingAgent->update([
                'club_id' => $invitation->club_id,
                'role'    => $invitation->role,
            ]);
            $invitation->update(['accepted_at' => now()]);

            return redirect()->route('login')
                ->with('success', 'You\'ve been added to ' . $invitation->club->name . '! Sign in to continue.');
        }

        // Show registration form
        return view('auth.invitation-register', compact('invitation'));
    }

    /**
     * Complete registration from an invitation.
     */
    public function completeRegistration(Request $request, $token)
    {
        $invitation = ClubInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired. Contact your club owner for a new one.');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $agent = Agent::create([
            'name'     => $validated['name'],
            'email'    => $invitation->email,
            'password' => Hash::make($validated['password']),
            'role'     => $invitation->role,
            'active'   => true,
            'club_id'  => $invitation->club_id,
        ]);

        $invitation->update(['accepted_at' => now()]);

        auth()->login($agent);

        return redirect()->intended('/dashboard')
            ->with('success', 'Welcome to ' . $invitation->club->name . '!');
    }

    /**
     * Cancel/revoke an invitation.
     */
    public function destroy(Request $request, ClubInvitation $invitation)
    {
        $agent = $request->user();
        abort_unless($invitation->club_id === $agent->club_id, 403);

        $invitation->delete();

        return redirect()->route('invitations.index')
            ->with('success', 'Invitation cancelled.');
    }

    /**
     * Remove an agent from the club.
     */
    public function removeAgent(Request $request, Agent $targetAgent)
    {
        $agent = $request->user();
        $club = $agent->club;

        abort_unless($targetAgent->club_id === $club->id, 403);
        abort_unless($agent->role === AgentRole::Owner, 403);
        abort_if($targetAgent->role === AgentRole::Owner, 403, 'Cannot remove the club owner.');

        $targetAgent->update(['club_id' => null, 'active' => false]);

        return redirect()->route('invitations.index')
            ->with('success', $targetAgent->name . ' has been removed from the club.');
    }
}

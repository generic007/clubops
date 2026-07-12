<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Services\ClubEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        // No agents yet? Redirect to first-run setup.
        if (Agent::count() === 0) {
            return redirect()->route('setup');
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $agent = Auth::user();

            // ── Decrypt and store the club encryption key in session ──
            if ($agent->club_id && $agent->club) {
                $encryptedBlob = $agent->club->encrypted_club_key;
                if ($encryptedBlob) {
                    $clubKey = ClubEncryptionService::decryptClubKey($encryptedBlob, $credentials['password']);
                    if ($clubKey) {
                        ClubEncryptionService::storeClubKeyInSession($clubKey, $agent->club_id);
                    }
                }
            }

            $agent->update(['last_login_at' => now()]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        $agent = Auth::user();
        if ($agent && $agent->club_id) {
            ClubEncryptionService::clearClubKeyFromSession($agent->club_id);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

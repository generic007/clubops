<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Club;
use App\Services\ClubKeyRecoveryService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    /**
     * Handle an incoming password reset request.
     *
     * Re-encrypts the club's encryption key with the new password.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker('agents')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Agent $agent, string $password) {
                // ── Re-encrypt club key with new password ──
                if ($agent->club_id && $agent->club) {
                    $club = $agent->club;

                    // Check if we have a recovery key escrow
                    if (ClubKeyRecoveryService::hasRecoveryKey($club)) {
                        $newEncryptedKey = ClubKeyRecoveryService::reEncryptWithNewPassword(
                            $club->server_encrypted_club_key,
                            $password
                        );

                        if ($newEncryptedKey) {
                            $club->update([
                                'encrypted_club_key' => $newEncryptedKey,
                            ]);
                        }
                    }
                }

                // Update the agent's password
                $agent->update([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ]);

                event(new PasswordReset($agent));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __('Your password has been reset!'))
            : back()->withErrors(['email' => __($status)]);
    }
}

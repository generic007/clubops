<?php

namespace App\Http\Middleware;

use App\Services\ClubEncryptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware: after successful authentication, load the club encryption
 * key into the session so Encryptable models can decrypt data.
 *
 * Must run AFTER the auth middleware.
 */
class LoadClubEncryptionKey
{
    public function handle(Request $request, Closure $next)
    {
        $agent = Auth::user();

        if ($agent && $agent->club_id) {
            $clubKey = ClubEncryptionService::getClubKeyFromSession($agent->club_id);

            // If the key isn't in the session yet, this means the session
            // was just created or the user needs to re-authenticate.
            // The key is loaded during login (AuthenticatedSessionController).
            if (!$clubKey) {
                // Check if it was just loaded
                ClubEncryptionService::getClubKeyFromSession($agent->club_id);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Automatically scope Eloquent queries to the authenticated agent's club.
 *
 * Usage:
 *   Route::middleware(['auth', 'scoped'])->group(...)
 *
 * Or in the controller constructor / base controller for global scoping.
 */
class ScopedByClub
{
    public function handle(Request $request, Closure $next)
    {
        $agent = Auth::user();

        if ($agent && $agent->club_id) {
            // Share the current club_id via a macro or service for model scoping
            // Models with WithClubScoping trait will use this automatically
            request()->merge(['_current_club_id' => $agent->club_id]);
        }

        return $next($request);
    }
}

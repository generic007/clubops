<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    /**
     * Require the agent's club to have an active subscription or valid trial.
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = Auth::guard('agent')->user();

        if (!$agent || !$agent->club_id) {
            // Not logged in as agent, or club-less — let auth middleware handle it
            return $next($request);
        }

        $club = $agent->club;

        if (!$club) {
            return redirect()->route('login');
        }

        // Allow if subscription is active
        if ($club->subscription_status === 'active') {
            return $next($request);
        }

        // Allow if trial is still valid
        if ($club->trial_ends_at && $club->trial_ends_at->isFuture()) {
            return $next($request);
        }

        // Redirect to billing page
        return redirect()->route('billing.index');
    }
}

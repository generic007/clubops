<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $agent = Auth::user();

        if (!$agent) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if ($agent->role->value === $role || $agent->role->value === 'owner') {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized. Required role: ' . implode(', ', $roles));
    }
}

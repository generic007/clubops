<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class PlayerAuthController extends Controller
{
    /**
     * Show the player login form.
     */
    public function showLoginForm()
    {
        return view('player.login');
    }

    /**
     * Handle player login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $player = Player::where('email', $credentials['email'])
            ->where('can_login', true)
            ->first();

        if (!$player || !Hash::check($credentials['password'], $player->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials or player portal not enabled.',
            ])->onlyInput('email');
        }

        if ($player->isExcluded()) {
            return back()->withErrors([
                'email' => 'Your account is currently restricted. Contact the club for details.',
            ])->onlyInput('email');
        }

        Auth::guard('player')->login($player, $request->boolean('remember'));

        $player->update(['last_login_at' => now()]);

        return redirect()->intended('/player/dashboard');
    }

    /**
     * Log the player out.
     */
    public function logout(Request $request)
    {
        Auth::guard('player')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/player/login');
    }

    /**
     * Player dashboard — their own stats.
     */
    public function dashboard()
    {
        /** @var Player $player */
        $player = Auth::guard('player')->user();

        $recentEntries = $player->ledgerLines()
            ->with('entry')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $recentTickets = $player->tickets()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $activePromos = $player->promoRedemptions()
            ->with('promotion')
            ->where('status', 'active')
            ->get();

        return view('player.dashboard', compact(
            'player', 'recentEntries', 'recentTickets', 'activePromos'
        ));
    }

    /**
     * Download a CSV statement of the player's transactions.
     */
    public function statement()
    {
        /** @var Player $player */
        $player = Auth::guard('player')->user();

        $lines = $player->ledgerLines()
            ->with('entry')
            ->orderBy('created_at', 'desc')
            ->get();

        $csv = "Date,Type,Description,Amount,Balance\n";
        $running = $player->balance();

        foreach ($lines as $line) {
            $amount = $line->credit > 0 ? $line->credit : -$line->debit;
            $csv .= implode(',', [
                $line->created_at->format('Y-m-d H:i'),
                $line->entry?->type->value ?? 'entry',
                '"' . str_replace('"', '""', $line->entry?->description ?? '') . '"',
                number_format($amount, 2),
                number_format($running, 2),
            ]) . "\n";
        }

        $filename = 'statement-' . now()->format('Y-m-d') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

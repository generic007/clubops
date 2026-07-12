<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the forgot-password form.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker('agents')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __('Password reset link sent! Check your email.'))
            : back()->withErrors(['email' => __($status)]);
    }
}

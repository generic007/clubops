<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ClubOps OS</title>
    <meta name="theme-color" content="#0f172a">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ================================================
                   CLUBOPS OS — Login Page
                   ================================================ */

        :root {
            --navy: #0f172a;
            --navy-light: #1e293b;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --accent: #8b5cf6;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #f1f5f9;
            --text-primary: #1e293b;
            --text-muted: #64748b;
            --card-border: #e2e8f0;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-pill: 9999px;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.06);
            --shadow-md: 0 4px 12px -2px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06);
            --shadow-lg: 0 10px 25px -5px rgb(0 0 0 / 0.1), 0 4px 10px -6px rgb(0 0 0 / 0.08);
            --shadow-xl: 0 20px 40px -8px rgb(0 0 0 / 0.12);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 50%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100dvh;
            margin: 0;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Subtle pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ================================================
                   LOGIN CARD
                   ================================================ */

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            animation: fadeSlideUp 0.5s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 40px 36px 36px;
            width: 100%;
            color: #e2e8f0;
            box-shadow:
                0 4px 24px rgba(0, 0, 0, 0.3),
                0 1px 4px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(51, 65, 85, 0.4);
        }

        /* Brand */
        .login-brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-brand .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 16px;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .login-brand .brand-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            margin: 0;
        }

        .login-brand .brand-title small {
            font-weight: 400;
            color: var(--primary-light, #93c5fd);
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .login-brand .brand-subtitle {
            font-size: 0.82rem;
            color: #94a3b8;
            margin-top: 6px;
            font-weight: 400;
        }

        /* Form */
        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 6px;
            display: block;
        }

        .login-form .input-wrap {
            position: relative;
        }

        .login-form .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.05rem;
            pointer-events: none;
            z-index: 2;
        }

        .login-form .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(71, 85, 105, 0.4);
            border-radius: var(--radius-sm);
            padding: 12px 14px 12px 42px;
            color: #e2e8f0;
            font-size: 0.95rem;
            width: 100%;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .login-form .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
            background: rgba(15, 23, 42, 0.8);
        }

        .login-form .form-control::placeholder {
            color: #64748b;
            opacity: 0.6;
        }

        .login-form .form-control:-webkit-autofill,
        .login-form .form-control:-webkit-autofill:hover,
        .login-form .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #e2e8f0;
            -webkit-box-shadow: 0 0 0px 1000px rgba(15, 23, 42, 0.9) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* No icon for password field by default */
        .login-form .input-wrap.no-icon .form-control {
            padding-left: 14px;
        }

        /* Remember Me — toggle switch */
        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            user-select: none;
        }

        .remember-wrap .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .remember-wrap .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .remember-wrap .toggle-switch .slider {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(71, 85, 105, 0.4);
            border-radius: var(--radius-pill);
            transition: all 0.3s ease;
        }

        .remember-wrap .toggle-switch .slider::before {
            content: '';
            position: absolute;
            left: 3px;
            bottom: 3px;
            width: 18px;
            height: 18px;
            background: #94a3b8;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .remember-wrap .toggle-switch input:checked + .slider {
            background: var(--primary);
        }

        .remember-wrap .toggle-switch input:checked + .slider::before {
            transform: translateX(20px);
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        .remember-wrap .toggle-label {
            font-size: 0.88rem;
            color: #94a3b8;
            font-weight: 500;
            cursor: pointer;
        }

        /* Submit Button */
        .login-form .btn-submit {
            background: linear-gradient(135deg, var(--primary), #2563eb);
            border: none;
            border-radius: var(--radius-pill);
            padding: 14px 24px;
            width: 100%;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.02em;
        }

        .login-form .btn-submit:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 6px 24px rgba(59, 130, 246, 0.45);
            transform: translateY(-2px);
        }

        .login-form .btn-submit:active {
            transform: translateY(0) scale(0.98);
        }

        .login-form .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.05),
                transparent
            );
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s ease;
        }

        .login-form .btn-submit:hover::after {
            transform: rotate(45deg) translateX(100%);
        }

        /* Error messages */
        .login-form .error-message {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #fca5a5;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-form .error-message .error-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .login-form .field-error {
            color: #fca5a5;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 0.75rem;
            color: #64748b;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-footer a:hover {
            color: var(--accent);
        }

        /* ================================================
                   RESPONSIVE
                   ================================================ */

        @media (max-width: 480px) {
            body {
                padding: 12px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .login-card {
                padding: 28px 24px 24px;
                border-radius: var(--radius-md);
            }

            .login-brand .brand-icon {
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
            }

            .login-brand .brand-title {
                font-size: 1.35rem;
            }

            .login-form .form-control {
                padding: 11px 12px 11px 38px;
                font-size: 0.9rem;
            }

            .login-form .btn-submit {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        @media (min-height: 800px) {
            body {
                padding-top: 0;
            }
        }

        /* Dark mode support on login */
        @media (prefers-color-scheme: dark) {
            /* Login page is already dark-themed, no changes needed */
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Brand -->
            <div class="login-brand">
                <div class="brand-icon">♠</div>
                <div class="brand-title">
                    ClubOps
                    <small>Operating System</small>
                </div>
                <div class="brand-subtitle">Private Poker Club Management</div>
            </div>

            <!-- Form -->
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- General Error Block -->
                @if($errors->any())
                    <div class="error-message">
                        <span class="error-icon">❌</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap no-icon">
                        <input type="password" name="password" id="password"
                               class="form-control"
                               placeholder="Enter your password"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Forgot Password? -->
                <div style="text-align:right; margin-bottom:6px;">
                    <a href="{{ route('password.request') }}"
                       style="color: #64748b; font-size: .82rem; text-decoration: none; transition: color .2s;">
                        Forgot password?
                    </a>
                </div>

                <!-- Remember Me — Toggle Switch -->
                <div class="remember-wrap">
                    <label class="toggle-switch">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <label class="toggle-label" for="remember">Remember this device</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="login-footer" style="margin-top: 32px;">
                <div style="margin-bottom: 16px; padding: 16px; background: rgba(59,130,246,.08); border: 1px solid rgba(59,130,246,.15); border-radius: var(--radius-md);">
                    <div style="font-size: .85rem; color: #94a3b8; margin-bottom: 8px;">Don't have a club yet?</div>
                    <a href="{{ route('register') }}" style="display: inline-block; background: linear-gradient(135deg, var(--primary), #2563eb); color: #fff; padding: 10px 24px; border-radius: var(--radius-pill); font-weight: 700; font-size: .88rem; text-decoration: none; transition: all .25s; box-shadow: 0 4px 12px rgba(59,130,246,.25);">
                        🚀 Create Your Club
                    </a>
                    <div style="font-size: .75rem; color: #64748b; margin-top: 6px;">Takes under a minute. Free to start.</div>
                </div>
                <div style="font-size: .72rem; color: #475569;">
                    {{ \App\ClubOpsEdition::label() }} &mdash; Secure Access
                </div>
            </div>
        </div>
    </div>
</body>
</html>

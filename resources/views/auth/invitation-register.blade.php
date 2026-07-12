<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join {{ $invitation->club->name }} — ClubOps</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #3b82f6; --accent: #8b5cf6; --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-pill: 9999px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100dvh; margin: 0; padding: 20px;
        }
        .wrapper { width: 100%; max-width: 440px; animation: fadeSlideUp .5s ease-out; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            color: #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.3);
            border: 1px solid rgba(51,65,85,.4);
        }
        .club-badge {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: var(--radius-md);
            padding: 16px;
            text-align: center;
            margin-bottom: 24px;
        }
        .club-badge .club-name { font-size: 1.1rem; font-weight: 700; color: #93c5fd; }
        .club-badge .club-role { font-size: 0.85rem; color: #94a3b8; margin-top: 4px; }
        .brand-title { text-align: center; font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 8px; letter-spacing: -0.03em; }
        .form-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; margin-bottom: 6px; display: block; }
        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(71, 85, 105, 0.4);
            border-radius: var(--radius-sm);
            padding: 12px 14px; color: #e2e8f0; width: 100%;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
            background: rgba(15, 23, 42, 0.8);
        }
        .btn-submit {
            background: linear-gradient(135deg, var(--primary), #2563eb);
            border: none; border-radius: var(--radius-pill);
            padding: 14px 24px; width: 100%;
            font-size: 0.95rem; font-weight: 700; color: #fff;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(59, 130, 246, 0.45); }
        .field-error { color: #fca5a5; font-size: 0.8rem; margin-top: 4px; }
        .mb-3 { margin-bottom: 18px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand-title">Join a Club</div>

            <div class="club-badge">
                <div class="club-name">🏛️ {{ $invitation->club->name }}</div>
                <div class="club-role">You've been invited as <strong>{{ ucfirst($invitation->role->value) }}</strong></div>
                @if($invitation->message)
                    <div class="mt-2" style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">
                        "{{ $invitation->message }}"
                    </div>
                @endif
            </div>

            @if($errors->any())
                <div class="mb-3" style="background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.2); border-radius: var(--radius-sm); padding: 12px; color: #fca5a5; font-size: 0.85rem;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('invitations.complete', $invitation->token) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="name">Your Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                           placeholder="e.g. Jordan Smith" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" value="{{ $invitation->email }}" disabled>
                    <small class="text-muted">This is pre-filled from your invitation.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Create Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="At least 8 characters" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Same password again" required>
                </div>

                <button type="submit" class="btn-submit">🚀 Join {{ $invitation->club->name }}</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Already have an account? <a href="{{ route('login') }}" style="color: var(--primary);">Sign in</a></small>
            </div>
        </div>
    </div>
</body>
</html>

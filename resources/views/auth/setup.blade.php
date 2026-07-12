<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(isset($hideModeSelector) && $hideModeSelector) Create Your Club — ClubOps OS @elseif(isset($isMultiTenant) && $isMultiTenant) Create Your Club — ClubOps OS @else Set Up — ClubOps OS @endif</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --navy:#0f172a; --navy-light:#1e293b; --primary:#3b82f6; --primary-dark:#2563eb; --accent:#8b5cf6; --success:#10b981; --danger:#ef4444; --text-muted:#64748b; --radius-sm:8px; --radius-md:12px; --radius-lg:16px; --radius-pill:9999px; }
        * { box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif;
            background: linear-gradient(135deg,#0f172a 0%,#1a1a3e 50%,#0f172a 100%);
            background-image: radial-gradient(ellipse at 20% 50%,rgba(59,130,246,.08) 0%,transparent 50%), radial-gradient(ellipse at 80% 50%,rgba(139,92,246,.06) 0%,transparent 50%), linear-gradient(135deg,#0f172a 0%,#1a1a3e 50%,#0f172a 100%);
            display:flex; align-items:center; justify-content:center;
            min-height:100dvh; margin:0; padding:20px;
        }
        body::before {
            content:''; position:fixed; top:0; left:0; right:0; bottom:0;
            background-image: radial-gradient(circle at 25% 25%,rgba(255,255,255,.02) 1px,transparent 1px), radial-gradient(circle at 75% 75%,rgba(255,255,255,.02) 1px,transparent 1px);
            background-size:40px 40px; pointer-events:none; z-index:0;
        }
        .wrapper { position:relative; z-index:1; width:100%; max-width:500px; animation:fadeSlideUp .5s ease-out; }
        @keyframes fadeSlideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .card {
            background:rgba(30,41,59,.95); backdrop-filter:blur(20px);
            border-radius:var(--radius-lg); padding:36px 32px;
            color:#e2e8f0; box-shadow:0 4px 24px rgba(0,0,0,.3); border:1px solid rgba(51,65,85,.4);
        }
        .brand { text-align:center; margin-bottom:24px; }
        .brand .icon {
            width:52px; height:52px; background:linear-gradient(135deg,var(--primary),var(--accent));
            border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center;
            font-size:1.4rem; margin:0 auto 12px; box-shadow:0 4px 16px rgba(59,130,246,.3);
        }
        .brand h1 { font-size:1.4rem; font-weight:800; color:#fff; letter-spacing:-.03em; margin:0; }
        .brand p { font-size:.82rem; color:#94a3b8; margin-top:4px; max-width:360px; margin-inline:auto; line-height:1.5; }

        .encryption-notice {
            background:rgba(139,92,246,.1); border:1px solid rgba(139,92,246,.15);
            border-radius:var(--radius-md); padding:12px 16px; margin-bottom:24px;
            text-align:center; font-size:.8rem; color:#c4b5fd;
        }
        .encryption-notice strong { color:#e2e8f0; }

        .error-box {
            background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.2);
            border-radius:var(--radius-sm); padding:12px 16px; margin-bottom:20px;
            color:#fca5a5; font-size:.85rem;
        }
        .form-group { margin-bottom:18px; }
        .form-label { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:6px; display:block; }
        .form-control {
            background:rgba(15,23,42,.6); border:1.5px solid rgba(71,85,105,.4);
            border-radius:var(--radius-sm); padding:12px 14px; color:#e2e8f0;
            font-size:.95rem; width:100%; transition:border-color .2s,box-shadow .2s;
        }
        .form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.15); outline:none; background:rgba(15,23,42,.8); }
        .form-control::placeholder { color:#64748b; opacity:.6; }
        .field-error { color:#fca5a5; font-size:.8rem; margin-top:4px; }
        .hint { font-size:.78rem; color:#64748b; margin-top:4px; }
        .section-divider { display:flex; align-items:center; gap:12px; margin:24px 0 18px; }
        .section-divider::before, .section-divider::after { content:''; flex:1; height:1px; background:rgba(71,85,105,.3); }
        .section-divider span { font-size:.78rem; color:#64748b; text-transform:uppercase; letter-spacing:.06em; }

        .btn-submit {
            background:linear-gradient(135deg,var(--primary),#2563eb); border:none;
            border-radius:var(--radius-pill); padding:14px 24px; width:100%;
            font-size:.95rem; font-weight:700; color:#fff; cursor:pointer;
            transition:all .25s; box-shadow:0 4px 16px rgba(59,130,246,.3); margin-top:8px;
        }
        .btn-submit:hover { background:linear-gradient(135deg,#2563eb,#1d4ed8); box-shadow:0 6px 24px rgba(59,130,246,.45); transform:translateY(-2px); }
        .btn-submit:active { transform:translateY(0) scale(.98); }

        .footer { text-align:center; margin-top:16px; font-size:.72rem; color:#475569; }
        .footer a { color:var(--primary); text-decoration:none; font-weight:600; }
        @media (max-width:480px) { body { padding:12px; align-items:flex-start; padding-top:24px; } .card { padding:28px 20px 24px; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">
                <div class="icon">♠</div>
                <h1>@if(isset($hideModeSelector) && $hideModeSelector) Create Your Club @elseif(isset($isMultiTenant) && $isMultiTenant) Create Your Club @else Set Up ClubOps @endif</h1>
                <p>{{ isset($isMultiTenant) && $isMultiTenant ? 'Name your club and create the owner account.' : 'Create your owner account. A default club will be created for you.' }}</p>
            </div>

            <!-- 🔐 Encryption notice -->
            <div class="encryption-notice">
                🔐 <strong>Zero-trust encryption active.</strong><br>
                Your data is encrypted with your password before it touches the database.
                Even the server operator cannot read it.
            </div>

            @if($errors->any())
                <div class="error-box">⚠️ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route(isset($hideModeSelector) && $hideModeSelector ? 'register.store' : 'setup.store') }}">
                @csrf
                <input type="hidden" name="multi_tenant" value="{{ isset($isMultiTenant) && $isMultiTenant ? '1' : '0' }}">

                @if(isset($isMultiTenant) && $isMultiTenant)
                <div class="section-divider"><span>🏛️ Your Club</span></div>
                <div class="form-group">
                    <label class="form-label" for="club_name">Club Name</label>
                    <input type="text" name="club_name" id="club_name" class="form-control @error('club_name') is-invalid @enderror"
                           value="{{ old('club_name', 'My Poker Club') }}" placeholder="e.g. Ace High Social Club" required autofocus>
                    <div class="hint">This will be the name players and team members see.</div>
                    @error('club_name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="club_description">Description <span style="font-weight:400;text-transform:none;color:#64748b;">(optional)</span></label>
                    <input type="text" name="club_description" id="club_description" class="form-control"
                           value="{{ old('club_description') }}" placeholder="e.g. Private poker club in Los Angeles">
                    @error('club_description')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                @else
                <div class="form-group" style="background:rgba(16,185,129,.08); padding:12px 16px; border-radius:var(--radius-sm); text-align:center;">
                    <div style="font-size:2rem; margin-bottom:4px;">🏠</div>
                    <strong style="color:#6ee7b7;">Single Club Mode</strong>
                    <div style="font-size:.82rem; color:#94a3b8; margin-top:4px;">
                        A default club will be created for you. No multi-tenancy — just you and your club.
                    </div>
                </div>
                @endif

                <div class="section-divider"><span>👤 {{ isset($isMultiTenant) && $isMultiTenant ? 'Club Owner' : 'Your Account' }}</span></div>

                <div class="form-group">
                    <label class="form-label" for="name">Your Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="e.g. Alex Rivera" required autocomplete="name">
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="At least 8 characters" required autocomplete="new-password">
                    <div class="hint">🔐 Used to derive your club's encryption key. If you lose it, data is unrecoverable.</div>
                    <div class="hint" style="margin-top: 3px; color: #6ee7b7;">✓ Your password is your encryption key. It never leaves your device.</div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                           placeholder="Same password again" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-submit">🚀 {{ isset($isMultiTenant) && $isMultiTenant ? 'Create My Club' : 'Get Started' }}</button>
            </form>

            <div class="footer">
                Already set up? <a href="{{ route('login') }}">Sign in →</a><br>
                <span style="opacity:0.6;">{{ \App\ClubOpsEdition::label() }}</span>
            </div>
        </div>
    </div>
</body>
</html>

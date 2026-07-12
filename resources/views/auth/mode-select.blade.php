<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started — ClubOps OS</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #3b82f6; --accent: #8b5cf6; --success: #10b981; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100dvh; margin: 0; padding: 20px;
        }
        .wrapper { width: 100%; max-width: 560px; animation: fadeSlideUp .5s ease-out; }
        @keyframes fadeSlideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .card {
            background: rgba(30,41,59,.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px 36px;
            color: #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.3);
            border: 1px solid rgba(51,65,85,.4);
        }
        .brand { text-align:center; margin-bottom:32px; }
        .brand .icon {
            width:56px; height:56px;
            background: linear-gradient(135deg,var(--primary),var(--accent));
            border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.6rem; margin:0 auto 12px;
            box-shadow:0 4px 16px rgba(59,130,246,.3);
        }
        .brand h1 { font-size:1.5rem; font-weight:800; color:#fff; letter-spacing:-.03em; margin:0; }
        .brand p { font-size:.85rem; color:#94a3b8; margin-top:6px; }

        .modes { display:flex; flex-direction:column; gap:16px; }
        .mode-card {
            background: rgba(15,23,42,.5);
            border: 1.5px solid rgba(71,85,105,.3);
            border-radius:12px;
            padding:24px;
            cursor:pointer;
            transition: all .25s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .mode-card:hover {
            border-color: var(--primary);
            background: rgba(59,130,246,.06);
            transform: translateY(-2px);
        }
        .mode-card .mode-icon { font-size:2rem; margin-bottom:12px; }
        .mode-card .mode-title { font-size:1.1rem; font-weight:700; color:#fff; margin-bottom:4px; }
        .mode-card .mode-desc { font-size:.82rem; color:#94a3b8; line-height:1.5; }
        .mode-card .mode-badge {
            display:inline-block;
            padding:3px 10px;
            border-radius:9999px;
            font-size:.7rem;
            font-weight:600;
            margin-top:10px;
        }
        .mode-card .mode-badge.private { background:rgba(16,185,129,.15); color:#6ee7b7; }
        .mode-card .mode-badge.public { background:rgba(59,130,246,.15); color:#93c5fd; }
        .mode-card .mode-features { margin-top:10px; font-size:.78rem; color:#64748b; line-height:1.6; }
        .mode-card .mode-features span { display:block; }

        .mode-card .encryption-badge {
            display:flex; align-items:center; gap:6px;
            margin-top:12px; padding:8px 12px;
            background:rgba(139,92,246,.1);
            border-radius:8px;
            font-size:.78rem; color:#c4b5fd;
        }

        .footer { text-align:center; margin-top:20px; font-size:.72rem; color:#475569; }
        .footer a { color:var(--primary); text-decoration:none; font-weight:600; }

        @media (max-width:480px) {
            .card { padding:28px 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">
                <div class="icon">♠</div>
                <h1>Welcome to ClubOps</h1>
                <p>Secure club operations, your way.</p>
            </div>

            <div class="modes">
                <!-- Single Club Mode -->
                <a href="?mode=single" class="mode-card">
                    <div class="mode-icon">🏠</div>
                    <div class="mode-title">Single Club — Private Install</div>
                    <div class="mode-desc">
                        One club, one owner. Perfect for a private poker club.
                        No multi-tenancy, no team management. Just you and your club.
                    </div>
                    <span class="mode-badge private">🔒 Private</span>
                    <div class="mode-features">
                        <span>✓ Auto-created default club</span>
                        <span>✓ No team/invite UI</span>
                        <span>✓ Simple, focused dashboard</span>
                    </div>
                    <div class="encryption-badge">
                        🔐 All data encrypted with your password — even I can't read it
                    </div>
                </a>

                <!-- Multi-Tenant Mode -->
                <a href="?mode=public" class="mode-card">
                    <div class="mode-icon">🌐</div>
                    <div class="mode-title">Multi-Club — Public Platform</div>
                    <div class="mode-desc">
                        Run a platform where multiple clubs each get their own
                        encrypted, isolated workspace. Invite team members, enable
                        player portals, manage roles.
                    </div>
                    <span class="mode-badge public">🌍 Public Platform</span>
                    <div class="mode-features">
                        <span>✓ Each club gets its own encryption key</span>
                        <span>✓ Invite team members with roles</span>
                        <span>✓ Player portal per club</span>
                        <span>✓ Full team management</span>
                    </div>
                    <div class="encryption-badge">
                        🔐 Per-club encryption — even the platform operator can't see inside
                    </div>
                </a>
            </div>

            <div class="footer">
                Already set up? <a href="{{ route('login') }}">Sign in →</a><br>
                <span style="margin-top:6px; display:inline-block; opacity:0.6;">{{ \App\ClubOpsEdition::label() }}</span>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired — ClubOps OS</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #3b82f6; --accent: #8b5cf6; --radius-lg: 16px; --radius-pill: 9999px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100dvh; margin: 0; padding: 20px;
        }
        .card {
            background: rgba(30,41,59,.95); backdrop-filter: blur(20px);
            border-radius: var(--radius-lg); padding: 40px 36px; max-width: 420px;
            color: #e2e8f0; text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,.3); border: 1px solid rgba(51,65,85,.4);
            animation: fadeSlideUp .5s ease-out;
        }
        @keyframes fadeSlideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .icon { font-size: 3rem; margin-bottom: 16px; }
        h1 { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
        p { font-size: .9rem; color: #94a3b8; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), #2563eb);
            border: none; border-radius: var(--radius-pill);
            padding: 14px 32px; font-size: .95rem; font-weight: 700;
            color: #fff; text-decoration: none;
            transition: all .25s;
            box-shadow: 0 4px 16px rgba(59,130,246,.3);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(59,130,246,.45); }
        .hint { font-size: .78rem; color: #64748b; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔄</div>
        <h1>Session Expired</h1>
        <p>Your session expired. This can happen after a server update or if the page was left open too long.</p>
        <a href="{{ route('register') }}" class="btn">🚀 Try Again</a>
        <div class="hint"><a href="{{ route('landing') }}" style="color: var(--primary);">Back to home →</a></div>
    </div>
</body>
</html>

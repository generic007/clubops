<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Expired — ClubOps</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100dvh; margin: 0; padding: 20px;
        }
        .card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px 36px;
            max-width: 440px;
            text-align: center;
            color: #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.3);
            border: 1px solid rgba(51,65,85,.4);
        }
        .icon { font-size: 3rem; margin-bottom: 16px; }
        h2 { font-weight: 700; margin-bottom: 8px; }
        p { color: #94a3b8; margin-bottom: 24px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 9999px; background: #3b82f6; color: #fff; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⏰</div>
        <h2>Invitation Expired</h2>
        <p>The invitation to <strong>{{ $invitation->club->name }}</strong> has expired. Invitations are valid for 7 days.</p>
        <a href="{{ route('login') }}" class="btn">Go to Login</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ClubOps OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100dvh; }
        .login-card { background: #1e293b; border-radius: 16px; padding: 40px; width: 100%; max-width: 400px; color: #e2e8f0; }
        .login-card h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 24px; }
        .login-card .form-control { background: #334155; border-color: #475569; color: #e2e8f0; }
        .login-card .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25); }
        .login-card .btn-primary { background: #2563eb; border: none; width: 100%; padding: 12px; font-weight: 700; }
        .login-card .btn-primary:hover { background: #1d4ed8; }
        .login-card .brand { text-align: center; margin-bottom: 32px; }
        .login-card .brand .logo { font-size: 2rem; font-weight: 800; color: #3b82f6; }
        .login-card .brand small { color: #64748b; display: block; }
        .error { color: #fca5a5; font-size: 0.85rem; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="logo">♠️ ClubOps <small>OS</small></div>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
    </div>
</body>
</html>

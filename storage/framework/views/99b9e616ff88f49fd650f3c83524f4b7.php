<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Portal — ClubOps</title>
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --primary-dark: #059669; --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-pill: 9999px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #064e3b 0%, #0d9488 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100dvh; margin: 0; padding: 20px;
        }
        .wrapper { width: 100%; max-width: 400px; animation: fadeSlideUp .5s ease-out; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card {
            background: rgba(255,255,255,0.95);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            box-shadow: 0 4px 24px rgba(0,0,0,.2);
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand .icon { font-size: 2rem; }
        .brand h1 { font-size: 1.3rem; font-weight: 800; color: #064e3b; margin: 8px 0 4px; }
        .brand p { font-size: 0.85rem; color: #64748b; margin: 0; }
        .form-label { font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 6px; display: block; }
        .form-control {
            border: 1.5px solid #e2e8f0; border-radius: var(--radius-sm);
            padding: 12px 14px; width: 100%; font-size: 0.95rem;
            transition: border-color .2s;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16,185,129,.15); outline: none; }
        .btn-submit {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none; border-radius: var(--radius-pill);
            padding: 14px 24px; width: 100%;
            font-size: 0.95rem; font-weight: 700; color: #fff;
            cursor: pointer; transition: all .25s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(16,185,129,.35); }
        .field-error { color: #dc2626; font-size: 0.8rem; margin-top: 4px; }
        .mb-3 { margin-bottom: 18px; }
        .footer { text-align: center; margin-top: 16px; font-size: 0.75rem; color: #94a3b8; }
        .admin-link { font-size: 0.75rem; text-align: center; margin-top: 12px; }
        .admin-link a { color: #94a3b8; text-decoration: none; }
        .admin-link a:hover { color: #64748b; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">
                <div class="icon">🃏</div>
                <h1>Player Portal</h1>
                <p>Check your balance, transactions, and promotions</p>
            </div>

            <?php if($errors->any()): ?>
                <div style="background: rgba(220,38,38,.08); border: 1px solid rgba(220,38,38,.15); border-radius: var(--radius-sm); padding: 12px; color: #dc2626; font-size: 0.85rem; margin-bottom: 18px;">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('player.login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           placeholder="you@example.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Your password" required>
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="admin-link">
                <a href="<?php echo e(route('login')); ?>">← Club staff? Sign in as admin</a>
            </div>

            <div class="footer">Club Operations System — Player Access</div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/player/login.blade.php ENDPATH**/ ?>
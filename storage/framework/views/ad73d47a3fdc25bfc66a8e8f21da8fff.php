<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — ClubOps OS</title>
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
            display:flex; align-items:center; justify-content:center;
            min-height:100dvh; margin:0; padding:20px;
        }
        body::before {
            content:''; position:fixed; top:0; left:0; right:0; bottom:0;
            background-image: radial-gradient(circle at 25% 25%,rgba(255,255,255,.02) 1px,transparent 1px);
            background-size:40px 40px; pointer-events:none; z-index:0;
        }
        .wrapper { position:relative; z-index:1; width:100%; max-width:420px; animation:fadeSlideUp .5s ease-out; }
        @keyframes fadeSlideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .card {
            background:rgba(30,41,59,.95); backdrop-filter:blur(20px);
            border-radius:var(--radius-lg); padding:36px 32px;
            color:#e2e8f0; box-shadow:0 4px 24px rgba(0,0,0,.3); border:1px solid rgba(51,65,85,.4);
        }
        .brand { text-align:center; margin-bottom:24px; }
        .brand .icon {
            width:48px; height:48px; background:linear-gradient(135deg,var(--primary),var(--accent));
            border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; margin:0 auto 12px;
        }
        .brand h1 { font-size:1.2rem; font-weight:700; color:#fff; margin:0; }
        .brand p { font-size:.82rem; color:#94a3b8; margin-top:4px; }

        .form-group { margin-bottom:16px; }
        .form-label { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:6px; display:block; }
        .form-control {
            background:rgba(15,23,42,.6); border:1.5px solid rgba(71,85,105,.4);
            border-radius:var(--radius-sm); padding:12px 14px; color:#e2e8f0;
            font-size:.95rem; width:100%; transition:border-color .2s,box-shadow .2s;
        }
        .form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.15); outline:none; }
        .form-control::placeholder { color:#64748b; opacity:.6; }
        .field-error { color:#fca5a5; font-size:.8rem; margin-top:4px; }
        .hint { font-size:.78rem; color:#64748b; margin-top:4px; }

        .btn-submit {
            background:linear-gradient(135deg,var(--primary),#2563eb); border:none;
            border-radius:var(--radius-pill); padding:14px 24px; width:100%;
            font-size:.95rem; font-weight:700; color:#fff; cursor:pointer;
            transition:all .25s; box-shadow:0 4px 16px rgba(59,130,246,.3); margin-top:8px;
        }
        .btn-submit:hover { background:linear-gradient(135deg,#2563eb,#1d4ed8); transform:translateY(-2px); }
        .btn-submit:active { transform:translateY(0) scale(.98); }

        .error-box {
            background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.2);
            border-radius:var(--radius-sm); padding:12px 16px; margin-bottom:20px;
            color:#fca5a5; font-size:.85rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">
                <div class="icon">🔐</div>
                <h1>Set New Password</h1>
                <p>Create a new password for your ClubOps account.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="error-box">⚠️ <?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.update')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <input type="hidden" name="email" value="<?php echo e($email); ?>">

                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="At least 8 characters" required autofocus autocomplete="new-password">
                    <div class="hint">🔐 Must be at least 8 characters. Longer is better.</div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"
                           placeholder="Same password again" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-submit">🔄 Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/auth/reset-password.blade.php ENDPATH**/ ?>
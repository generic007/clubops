<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClubOps OS — Private Poker Club Operations, Simplified</title>
    <meta name="description" content="Manage players, track sessions, reconcile ledgers, and grow your poker club — all with zero-trust encryption.">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" type="image/svg+xml" href="/logo.svg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ================================================
           CLUBOPS OS — Landing Page
           ================================================ */

        :root {
            --navy: #0f172a;
            --navy-light: #1e293b;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --accent: #8b5cf6;
            --success: #10b981;
            --danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-pill: 9999px;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.06);
            --shadow-md: 0 4px 12px -2px rgb(0 0 0 / 0.08);
            --shadow-lg: 0 10px 25px -5px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 40px -8px rgb(0 0 0 / 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            background-image:
                radial-gradient(ellipse at 15% 20%, rgba(59, 130, 246, 0.10) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 30%, rgba(139, 92, 246, 0.07) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, #0f172a 0%, #1a1a3e 50%, #0f172a 100%);
            color: #e2e8f0;
            min-height: 100dvh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            flex-direction: column;
        }

        /* Subtle pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ================================================
           KEYFRAMES
           ================================================ */

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 4px 24px rgba(59, 130, 246, 0.25); }
            50% { box-shadow: 0 6px 32px rgba(59, 130, 246, 0.45); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ================================================
           LAYOUT
           ================================================ */

        .landing-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100dvh;
        }

        /* ================================================
           NAV
           ================================================ */

        .landing-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            animation: fadeSlideUp 0.4s ease-out 0.1s both;
        }

        .landing-nav .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #ffffff;
        }

        .landing-nav .nav-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .landing-nav .nav-brand .brand-text {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .landing-nav .nav-brand .brand-text small {
            font-weight: 400;
            color: var(--primary);
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: block;
            margin-top: 1px;
        }

        .landing-nav .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .landing-nav .nav-links a {
            text-decoration: none;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            transition: all 0.2s ease;
        }

        .landing-nav .nav-links a:hover {
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.05);
        }

        .landing-nav .nav-links a.nav-sign-in {
            border: 1.5px solid rgba(59, 130, 246, 0.3);
            color: var(--primary);
            font-weight: 600;
        }

        .landing-nav .nav-links a.nav-sign-in:hover {
            border-color: var(--primary);
            background: rgba(59, 130, 246, 0.08);
        }

        /* ================================================
           HERO
           ================================================ */

        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px 60px;
            animation: fadeSlideUp 0.5s ease-out 0.2s both;
        }

        .hero-container {
            max-width: 1100px;
            width: 100%;
        }

        .hero-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: var(--radius-lg);
            padding: 56px 48px 48px;
            text-align: center;
            box-shadow:
                0 4px 32px rgba(0, 0, 0, 0.3),
                0 1px 4px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(51, 65, 85, 0.4);
            position: relative;
            overflow: hidden;
        }

        /* Subtle accent glow in card */
        .hero-card::before {
            content: '';
            position: absolute;
            top: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-card .edition-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: var(--radius-pill);
            padding: 6px 16px;
            font-size: 0.72rem;
            font-weight: 600;
            color: #93c5fd;
            letter-spacing: 0.03em;
            margin-bottom: 28px;
            animation: fadeSlideUp 0.5s ease-out 0.3s both;
        }

        .hero-card .edition-badge .badge-dot {
            width: 6px;
            height: 6px;
            background: var(--success);
            border-radius: 50%;
            animation: pulseGlow 2s ease-in-out infinite;
        }

        .hero-card h1 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.04em;
            line-height: 1.15;
            margin-bottom: 16px;
            animation: fadeSlideUp 0.5s ease-out 0.35s both;
        }

        .hero-card h1 .highlight {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-card .subhead {
            font-size: clamp(0.95rem, 1.5vw, 1.1rem);
            color: #94a3b8;
            line-height: 1.6;
            max-width: 640px;
            margin: 0 auto 36px;
            font-weight: 400;
            animation: fadeSlideUp 0.5s ease-out 0.4s both;
        }

        .hero-card .cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            animation: fadeSlideUp 0.5s ease-out 0.45s both;
        }

        .hero-card .cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary), #2563eb);
            border: none;
            border-radius: var(--radius-pill);
            padding: 14px 32px;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .hero-card .cta-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 6px 28px rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        .hero-card .cta-primary:active {
            transform: translateY(0) scale(0.98);
        }

        .hero-card .cta-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.06),
                transparent
            );
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s ease;
        }

        .hero-card .cta-primary:hover::after {
            transform: rotate(45deg) translateX(100%);
        }

        .hero-card .cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1.5px solid rgba(71, 85, 105, 0.5);
            border-radius: var(--radius-pill);
            padding: 14px 28px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
        }

        .hero-card .cta-secondary:hover {
            border-color: rgba(59, 130, 246, 0.4);
            color: #e2e8f0;
            background: rgba(59, 130, 246, 0.06);
            transform: translateY(-2px);
        }

        .hero-card .cta-secondary:active {
            transform: translateY(0) scale(0.98);
        }

        /* Trust indicator below CTAs */
        .hero-card .trust-line {
            margin-top: 28px;
            font-size: 0.75rem;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            animation: fadeIn 0.6s ease-out 0.6s both;
        }

        .hero-card .trust-line span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ================================================
           FEATURES
           ================================================ */

        .features-section {
            padding: 60px 24px 80px;
            animation: fadeSlideUp 0.5s ease-out 0.5s both;
        }

        .features-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .features-header h2 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            margin-bottom: 10px;
        }

        .features-header p {
            font-size: 0.95rem;
            color: #64748b;
            max-width: 520px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .feature-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: var(--radius-md);
            padding: 24px 22px;
            border: 1px solid rgba(51, 65, 85, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }

        .feature-card:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(59, 130, 246, 0.2);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .feature-card .feature-icon {
            font-size: 1.6rem;
            margin-bottom: 12px;
            display: block;
        }

        .feature-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .feature-card p {
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.5;
            font-weight: 400;
        }

        /* ================================================
           FOOTER
           ================================================ */

        .landing-footer {
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid rgba(51, 65, 85, 0.3);
            animation: fadeSlideUp 0.5s ease-out 0.55s both;
        }

        .landing-footer .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            font-size: 0.78rem;
            color: #475569;
        }

        .landing-footer .footer-inner .edition-label {
            color: #6ee7b7;
            font-weight: 500;
        }

        .landing-footer .footer-inner a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .landing-footer .footer-inner a:hover {
            color: var(--primary);
        }

        .landing-footer .footer-inner .divider {
            width: 3px;
            height: 3px;
            background: #475569;
            border-radius: 50%;
            display: inline-block;
        }

        /* ================================================
           RESPONSIVE
           ================================================ */

        @media (max-width: 768px) {
            .landing-nav {
                padding: 16px 20px;
                flex-direction: column;
                gap: 12px;
            }

            .landing-nav .nav-links {
                width: 100%;
                justify-content: center;
            }

            .hero-card {
                padding: 36px 24px 32px;
                border-radius: var(--radius-md);
            }

            .hero-card h1 {
                font-size: clamp(1.6rem, 6vw, 2rem);
            }

            .hero-card .cta-group {
                flex-direction: column;
                width: 100%;
            }

            .hero-card .cta-primary,
            .hero-card .cta-secondary {
                width: 100%;
                justify-content: center;
                padding: 13px 24px;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .features-section {
                padding: 40px 16px 60px;
            }

            .feature-card {
                padding: 20px 18px;
            }

            .landing-footer {
                padding: 20px 16px;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 20px 12px 40px;
            }

            .hero-card {
                padding: 28px 18px 24px;
            }

            .hero-card .edition-badge {
                font-size: 0.65rem;
                padding: 5px 12px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .features-header {
                margin-bottom: 32px;
            }

            .landing-nav .nav-links a {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="landing-wrapper">
        <!-- Navigation -->
        <nav class="landing-nav">
            <a href="/" class="nav-brand">
                <span class="brand-icon">♠</span>
                <span class="brand-text">
                    ClubOps
                    <small>Operating System</small>
                </span>
            </a>
            <div class="nav-links">
                <a href="<?php echo e(route('register')); ?>">Get Started</a>
                <a href="<?php echo e(route('login')); ?>" class="nav-sign-in">Sign In</a>
            </div>
        </nav>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-card">
                    <!-- Edition badge -->
                    <div class="edition-badge">
                        <span class="badge-dot"></span>
                        <?php echo e(\App\ClubOpsEdition::label()); ?>

                    </div>

                    <h1>
                        Private Poker Club<br>
                        Operations, <span class="highlight">Simplified</span>
                    </h1>

                    <p class="subhead">
                        Manage players, track sessions, reconcile ledgers, and grow your club —<br>
                        all with enterprise-grade security and zero-trust encryption.
                    </p>

                    <div class="cta-group">
                        <a href="<?php echo e(route('register')); ?>" class="cta-primary">
                            🚀 Create Your Club
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="cta-secondary">
                            Sign In →
                        </a>
                    </div>

                    <div class="trust-line">
                        <span>🔐 Zero-trust encrypted</span>
                        <span>·</span>
                        <span>⚡ Takes under a minute to start</span>
                        <span>·</span>
                        <span>🆓 Free to begin</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="features-section" id="features">
            <div class="features-container">
                <div class="features-header">
                    <h2>Everything you need to run your club</h2>
                    <p>From player onboarding to nightly reconciliation — built for real poker operations.</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">🃏</span>
                        <h3>Player CRM</h3>
                        <p>Full player profiles with notes, tags, contact history, and compliance tracking. Know every player at your table.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">📒</span>
                        <h3>Double-Entry Ledger</h3>
                        <p>Immutable transaction history with real-time balances. Every buy-in, cash-out, and adjustment is auditable.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">🔄</span>
                        <h3>Daily Reconciliation</h3>
                        <p>Automated end-of-day closing with exception reporting and full audit trails. Close with confidence every night.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">🎁</span>
                        <h3>Promotion Engine</h3>
                        <p>Create, track, and redeem promotions with real-time liability reports. Know exactly what every promo costs.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">🎫</span>
                        <h3>Support Tickets</h3>
                        <p>Player-facing dispute and request management with threaded comments, status tracking, and exportable history.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">📊</span>
                        <h3>Reports &amp; Exports</h3>
                        <p>Player statements, daily ledgers, agent exposure reports, and compliance summaries — all one click away.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">🔐</span>
                        <h3>Zero-Trust Encryption</h3>
                        <p>Data encrypted with your password before it touches the database. Even the server operator cannot read it.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">👥</span>
                        <h3>Team Management</h3>
                        <p>Role-based access for owners, managers, accountants, and agents. Granular permissions for every team member.</p>
                    </div>

                    <div class="feature-card">
                        <span class="feature-icon">⚡</span>
                        <h3>Quick Entry &amp; Actions</h3>
                        <p>Fast player search, instant buy-in/cash-out, and keyboard-friendly workflows. Built for the speed of live operations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="landing-footer">
            <div class="footer-inner">
                <span class="edition-label">🔒 <?php echo e(\App\ClubOpsEdition::label()); ?></span>
                <span class="divider"></span>
                <a href="<?php echo e(route('register')); ?>">Create Your Club</a>
                <span class="divider"></span>
                <a href="<?php echo e(route('login')); ?>">Sign In</a>
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/landing.blade.php ENDPATH**/ ?>
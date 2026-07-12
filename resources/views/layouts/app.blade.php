<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>@yield('title', 'ClubOps OS') — ClubOps</title>
    <meta name="description" content="Private poker club operations system">
    <meta name="theme-color" content="#0f172a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        /* ================================================
                   CLUBOPS OS — World-Class Admin Panel
                   ================================================ */

        /* --- Design Tokens --- */
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 0px;
            --bottom-nav-height: 64px;
            --top-header-height: 60px;

            --navy: #0f172a;
            --navy-light: #1e293b;
            --navy-lighter: #334155;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #93c5fd;
            --accent: #8b5cf6;
            --accent-light: #a78bfa;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #06b6d4;
            --info-light: #cffafe;

            --bg: #f1f5f9;
            --bg-dark: #0f172a;
            --card-bg: #ffffff;
            --card-bg-dark: #1e293b;
            --card-border: #e2e8f0;
            --card-border-dark: #334155;
            --text-primary: #1e293b;
            --text-muted: #64748b;
            --text-on-dark: #e2e8f0;
            --text-on-darker: #94a3b8;

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-pill: 9999px;

            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.06);
            --shadow-md: 0 4px 12px -2px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06);
            --shadow-lg: 0 10px 25px -5px rgb(0 0 0 / 0.1), 0 4px 10px -6px rgb(0 0 0 / 0.08);
            --shadow-xl: 0 20px 40px -8px rgb(0 0 0 / 0.12);
        }

        /* --- Base --- */
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100dvh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Prevent flash-of-wrong-theme */
        html[data-bs-theme="dark"] body,
        body.dark,
            --bg: #0a0f1e;
            --card-bg: #1e293b;
            --text-primary: #e2e8f0;
        }

        /* --- Typography --- */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        h1.h3 { font-size: 1.5rem; }

        /* Monospace for financial data */
        .font-mono, .amount, td.amount, .kpi-value {
            font-family: 'SF Mono', 'SFMono-Regular', 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
        }

        /* Uppercase labels */
        .label-uppercase {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        /* ================================================
                   LAYOUT
                   ================================================ */

        .app-layout { display: flex; min-height: 100dvh; }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a 0%, #0b1120 50%, #0a0f1e 100%);
            background-image:
                radial-gradient(ellipse at 0% 0%, rgba(59, 130, 246, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 100% 100%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                linear-gradient(180deg, #0f172a 0%, #0b1120 50%, #0a0f1e 100%);
            color: var(--text-on-darker);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100dvh;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: var(--navy-lighter) transparent;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--navy-lighter); border-radius: 4px; }

        /* Brand Area */
        .sidebar-brand {
            padding: 24px 20px 20px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), rgba(139, 92, 246, 0.3), transparent);
        }

        .sidebar-brand a {
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .sidebar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .sidebar-brand small {
            font-weight: 400;
            color: var(--primary-light);
            font-size: 0.65rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .sidebar-brand .brand-subtitle {
            font-size: 0.65rem;
            color: var(--text-on-darker);
            font-weight: 400;
            letter-spacing: 0.05em;
            margin-left: 46px;
            margin-top: -2px;
        }

        /* Divider */
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(51, 65, 85, 0.8), transparent);
            margin: 6px 16px;
            border: 0;
        }

        /* Nav Items */
        .sidebar-nav { flex: 1; padding: 8px 0; }

        .sidebar-section-label {
            padding: 16px 20px 6px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(148, 163, 184, 0.5);
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: var(--text-on-darker);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            margin: 1px 8px;
            border-radius: var(--radius-sm);
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-on-dark);
        }

        .sidebar-nav a.active {
            background: rgba(59, 130, 246, 0.12);
            color: var(--primary-light);
            font-weight: 600;
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: linear-gradient(180deg, var(--primary), var(--accent));
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav a .nav-icon {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav a .nav-text {
            flex: 1;
        }

        .sidebar-nav a .nav-badge {
            font-size: 0.65rem;
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 1px 7px;
            border-radius: var(--radius-pill);
            font-weight: 600;
        }

        /* Nav item collapse animation */
        .sidebar-nav a {
            transform: translateX(0);
        }
        .sidebar-nav a:hover {
            transform: translateX(3px);
        }

        /* --- Sidebar Footer --- */
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(51, 65, 85, 0.6);
            font-size: 0.8rem;
        }

        .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-footer .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-pill);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-footer .user-name {
            font-weight: 600;
            color: var(--text-on-dark);
            font-size: 0.85rem;
        }

        .sidebar-footer .user-role {
            font-size: 0.7rem;
            color: var(--text-on-darker);
        }

        .sidebar-footer .logout-btn {
            background: none;
            border: none;
            color: var(--danger);
            padding: 0;
            font-size: 0.78rem;
            cursor: pointer;
            transition: opacity 0.2s;
            opacity: 0.7;
        }
        .sidebar-footer .logout-btn:hover { opacity: 1; }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            margin-top: 8px;
            cursor: pointer;
            color: var(--text-on-darker);
            font-size: 0.78rem;
            border-top: 1px solid rgba(51, 65, 85, 0.4);
            padding-top: 12px;
        }

        .dark-mode-toggle .toggle-track {
            width: 40px;
            height: 22px;
            background: var(--navy-lighter);
            border-radius: var(--radius-pill);
            position: relative;
            transition: background 0.3s ease;
            flex-shrink: 0;
        }

        .dark-mode-toggle .toggle-track .toggle-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            background: #fbbf24;
            border-radius: 50%;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
        }

        body.dark .dark-mode-toggle .toggle-track {
            background: var(--primary);
        }
        body.dark .dark-mode-toggle .toggle-track .toggle-thumb {
            transform: translateX(18px);
            background: var(--navy);
        }

        /* --- Main Wrapper --- */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100dvh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - var(--sidebar-width));
        }

        /* --- Top Header --- */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            padding: 0 24px;
            height: var(--top-header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        body.dark .top-header {
            background: rgba(15, 23, 42, 0.85);
            border-color: var(--card-border-dark);
        }

        .top-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .top-header-left .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            display: none;
        }

        .top-header-left .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            transition: background 0.2s;
        }
        .top-header-left .hamburger:hover { background: rgba(0,0,0,0.05); }

        .top-header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-header-right .header-btn {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-pill);
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-muted);
            position: relative;
            transition: all 0.2s ease;
        }
        .top-header-right .header-btn:hover {
            background: rgba(0,0,0,0.05);
            color: var(--text-primary);
        }
        body.dark .top-header-right .header-btn:hover {
            background: rgba(255,255,255,0.08);
        }

        .top-header-right .header-btn .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid white;
        }
        body.dark .top-header-right .header-btn .badge-dot {
            border-color: var(--navy);
        }

        .top-header-right .user-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px 4px 4px;
            border-radius: var(--radius-pill);
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            background: transparent;
            color: var(--text-primary);
        }
        .top-header-right .user-dropdown:hover {
            background: rgba(0,0,0,0.05);
        }
        body.dark .top-header-right .user-dropdown:hover {
            background: rgba(255,255,255,0.08);
        }

        .top-header-right .user-dropdown .avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-pill);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
        }

        .top-header-right .user-dropdown .dropdown-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* --- Main Content --- */
        .main-content {
            flex: 1;
            padding: 24px;
            padding-bottom: calc(24px + var(--bottom-nav-height));
            animation: fadeSlideUp 0.35s ease-out;
            width: 100%;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ================================================
                   CARDS
                   ================================================ */

        .card, .kpi-card {
            border: none;
            border-radius: var(--radius-md);
            background: var(--card-bg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.25s ease, transform 0.25s ease, background 0.3s ease;
        }

        body.dark .card,
        body.dark .kpi-card {
            background: var(--card-bg-dark);
        }

        .card:hover,
        .kpi-card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--card-border);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--radius-md) var(--radius-md) 0 0 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        body.dark .card-header {
            border-color: var(--card-border-dark);
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background: transparent;
            border-top: 1px solid var(--card-border);
            padding: 12px 20px;
            border-radius: 0 0 var(--radius-md) var(--radius-md) !important;
        }

        body.dark .card-footer {
            border-color: var(--card-border-dark);
        }

        /* KPI Cards */
        .kpi-card {
            padding: 18px 20px;
            overflow: hidden;
            position: relative;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--accent));
            border-radius: 0 3px 3px 0;
        }

        .kpi-card .kpi-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-pill);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .kpi-card .kpi-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-primary);
            letter-spacing: -0.03em;
        }

        .kpi-card .kpi-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* KPI text-center variant (used in compliance/reports) */
        .kpi-card.text-center::before {
            display: none;
        }

        /* ================================================
                   TABLES
                   ================================================ */

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            background: var(--bg);
            border-bottom: 2px solid var(--card-border);
            padding: 14px 16px;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        body.dark .table thead th {
            background: rgba(15, 23, 42, 0.6);
            border-color: var(--card-border-dark);
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-primary);
        }

        body.dark .table td {
            border-color: var(--card-border-dark);
        }

        .table tbody tr {
            transition: background 0.15s ease;
        }

        .table tbody tr:hover {
            background: rgba(59, 130, 246, 0.04);
        }

        body.dark .table tbody tr:hover {
            background: rgba(59, 130, 246, 0.08);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Striped */
        .table-striped tbody tr:nth-of-type(odd) {
            background: rgba(0,0,0,0.02);
        }
        body.dark .table-striped tbody tr:nth-of-type(odd) {
            background: rgba(255,255,255,0.02);
        }

        /* Table-sm */
        .table-sm th { padding: 10px 12px; }
        .table-sm td { padding: 8px 12px; }

        /* Financial columns */
        .table td.amount,
        .table td:nth-child(3).amount,
        .table td.mono-amount {
            font-family: 'SF Mono', 'SFMono-Regular', 'JetBrains Mono', monospace;
            text-align: right;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table td.amount.negative,
        .table td.text-danger.mono-amount {
            color: var(--danger) !important;
        }

        /* ================================================
                   BADGES
                   ================================================ */

        .badge {
            font-weight: 600;
            font-size: 0.72rem;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            letter-spacing: 0.02em;
        }

        /* Soft background badges (override Bootstrap defaults) */
        .badge.bg-primary { background: rgba(59, 130, 246, 0.12) !important; color: #1d4ed8 !important; }
        .badge.bg-secondary { background: rgba(100, 116, 139, 0.12) !important; color: #475569 !important; }
        .badge.bg-success { background: rgba(16, 185, 129, 0.12) !important; color: #047857 !important; }
        .badge.bg-warning { background: rgba(245, 158, 11, 0.12) !important; color: #b45309 !important; }
        .badge.bg-danger { background: rgba(239, 68, 68, 0.12) !important; color: #b91c1c !important; }
        .badge.bg-info { background: rgba(6, 182, 212, 0.12) !important; color: #0e7490 !important; }
        .badge.bg-dark { background: rgba(15, 23, 42, 0.12) !important; color: #0f172a !important; }
        .badge.bg-light { background: rgba(241, 245, 249, 0.8) !important; color: #475569 !important; }

        body.dark .badge.bg-primary { background: rgba(59, 130, 246, 0.35) !important; color: #b9d8ff !important; }
        body.dark .badge.bg-secondary { background: rgba(100, 116, 139, 0.35) !important; color: #cbd5e1 !important; }
        body.dark .badge.bg-success { background: rgba(16, 185, 129, 0.3) !important; color: #96f0c8 !important; }
        body.dark .badge.bg-warning { background: rgba(245, 158, 11, 0.35) !important; color: #fde68a !important; }
        body.dark .badge.bg-danger { background: rgba(239, 68, 68, 0.35) !important; color: #fecaca !important; }
        body.dark .badge.bg-info { background: rgba(6, 182, 212, 0.35) !important; color: #a5f3fc !important; }
        body.dark .badge.bg-dark { background: rgba(255, 255, 255, 0.2) !important; color: #f1f5f9 !important; }

        /* Status badges */
        .badge-status {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            display: inline-block;
            letter-spacing: 0.02em;
        }
        .badge-active { background: rgba(16, 185, 129, 0.12); color: #047857; }
        .badge-lead { background: rgba(245, 158, 11, 0.12); color: #b45309; }
        .badge-pending { background: rgba(59, 130, 246, 0.12); color: #1d4ed8; }
        .badge-inactive { background: rgba(100, 116, 139, 0.12); color: #475569; }
        .badge-vip { background: rgba(245, 158, 11, 0.15); color: #92400e; }
        .badge-suspended,
        .badge-banned,
        .badge-excluded { background: rgba(239, 68, 68, 0.12); color: #b91c1c; }
        .badge-open { background: rgba(59, 130, 246, 0.12); color: #1d4ed8; }
        .badge-closed { background: rgba(100, 116, 139, 0.12); color: #475569; }

        body.dark .badge-active { background: rgba(16, 185, 129, 0.3); color: #96f0c8; }
        body.dark .badge-lead { background: rgba(245, 158, 11, 0.35); color: #fde68a; }
        body.dark .badge-pending { background: rgba(59, 130, 246, 0.35); color: #b9d8ff; }
        body.dark .badge-inactive { background: rgba(100, 116, 139, 0.35); color: #cbd5e1; }
        body.dark .badge-vip { background: rgba(245, 158, 11, 0.35); color: #fde68a; }
        body.dark .badge-suspended,
        body.dark .badge-banned,
        body.dark .badge-excluded { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
        body.dark .badge-open { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
        body.dark .badge-closed { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }

        /* ================================================
                   BUTTONS
                   ================================================ */

        .btn {
            border-radius: var(--radius-pill);
            padding: 9px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn:active {
            transform: scale(0.97);
        }

        /* Primary — gradient */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #2563eb);
            border: none;
            color: #fff;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
            transform: translateY(-1px);
            color: #fff;
        }
        .btn-primary:active { transform: translateY(0) scale(0.97); }

        /* Outline */
        .btn-outline-primary {
            border-color: rgba(59, 130, 246, 0.3);
            color: var(--primary);
            background: transparent;
        }
        .btn-outline-primary:hover {
            border-color: var(--primary);
            background: rgba(59, 130, 246, 0.06);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border-color: rgba(100, 116, 139, 0.3);
            color: var(--text-muted);
        }
        .btn-outline-secondary:hover {
            border-color: rgba(100, 116, 139, 0.5);
            background: rgba(100, 116, 139, 0.06);
            transform: translateY(-1px);
            color: var(--text-primary);
        }

        .btn-outline-success {
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--success);
        }
        .btn-outline-success:hover {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.06);
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            border-color: rgba(239, 68, 68, 0.3);
            color: var(--danger);
        }
        .btn-outline-danger:hover {
            border-color: var(--danger);
            background: rgba(239, 68, 68, 0.06);
            transform: translateY(-1px);
        }

        .btn-outline-warning {
            border-color: rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }
        .btn-outline-warning:hover {
            border-color: var(--warning);
            background: rgba(245, 158, 11, 0.06);
            transform: translateY(-1px);
        }

        .btn-outline-info {
            border-color: rgba(6, 182, 212, 0.3);
            color: var(--info);
        }
        .btn-outline-info:hover {
            border-color: var(--info);
            background: rgba(6, 182, 212, 0.06);
            transform: translateY(-1px);
        }

        /* Sizes */
        .btn-sm {
            padding: 5px 14px;
            font-size: 0.78rem;
        }

        .btn-lg {
            padding: 12px 28px;
            font-size: 0.95rem;
        }

        /* Icon-only buttons (used in tables) */
        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-pill);
        }

        .btn-icon.btn-sm {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }

        /* ================================================
                   FORMS
                   ================================================ */

        .form-control,
        .form-select {
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            border: 1.5px solid var(--card-border);
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
        }

        body.dark .form-control,
        body.dark .form-select {
            background: #0f172a;
            border-color: var(--card-border-dark);
            color: var(--text-on-dark);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }

        .form-control-sm {
            padding: 7px 12px;
            font-size: 0.82rem;
        }

        .form-control-lg {
            padding: 14px 18px;
            font-size: 1rem;
        }

        .form-check-input {
            border-radius: 3px;
            border: 1.5px solid var(--card-border);
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-check-label {
            font-size: 0.88rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: var(--danger);
        }

        .input-group .btn {
            border-radius: var(--radius-sm);
        }

        .input-group > :first-child {
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        }
        .input-group > :last-child {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* ================================================
                   ALERTS & FLASH
                   ================================================ */

        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            font-size: 0.88rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert .alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
        }
        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
        }
        .alert-warning {
            background: var(--warning-light);
            color: #78350f;
        }
        .alert-info {
            background: var(--info-light);
            color: #155e75;
        }

        body.dark .alert-success { background: rgba(16, 185, 129, 0.25); color: #96f0c8; }
        body.dark .alert-danger { background: rgba(239, 68, 68, 0.25); color: #fecaca; }
        body.dark .alert-warning { background: rgba(245, 158, 11, 0.25); color: #fde68a; }
        body.dark .alert-info { background: rgba(6, 182, 212, 0.25); color: #a5f3fc; }

        .alert-dismissible .btn-close {
            padding: 16px;
            font-size: 0.75rem;
        }

        /* Flash messages */
        .alert-flash {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9999;
            max-width: 420px;
            min-width: 300px;
            animation: slideInRight 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-lg);
        }

        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        /* ================================================
                   MODAL
                   ================================================ */

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            background: var(--card-bg);
        }

        body.dark .modal-content {
            background: var(--card-bg-dark);
        }

        .modal-header {
            border-bottom: 1px solid var(--card-border);
            padding: 20px 24px 16px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        body.dark .modal-header {
            border-color: var(--card-border-dark);
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .modal-header .btn-close {
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .modal-header .btn-close:hover { opacity: 1; }

        .modal-body {
            padding: 20px 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--card-border);
            padding: 16px 24px 20px;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            gap: 8px;
        }

        body.dark .modal-footer {
            border-color: var(--card-border-dark);
        }

        /* Smooth modal animations */
        .modal.fade .modal-dialog {
            transform: scale(0.95) translateY(-20px);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
            opacity: 0;
        }

        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* Frosted backdrop */
        .modal-backdrop.show {
            opacity: 0.5;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* ================================================
                   PAGINATION
                   ================================================ */

        .pagination {
            margin: 0;
            gap: 4px;
        }

        .pagination .page-link {
            border: none;
            border-radius: var(--radius-pill) !important;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background: rgba(59, 130, 246, 0.08);
            color: var(--primary);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), #2563eb);
            color: #fff;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.4;
            pointer-events: none;
        }

        body.dark .pagination .page-link {
            color: var(--text-on-darker);
        }
        body.dark .pagination .page-link:hover {
            background: rgba(59, 130, 246, 0.15);
        }

        /* ================================================
                   EMPTY STATES
                   ================================================ */

        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-state .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 16px;
            display: block;
            line-height: 1;
        }

        .empty-state .empty-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .empty-state .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state .empty-action {
            margin-top: 4px;
        }

        /* ================================================
                   PROGRESS BARS
                   ================================================ */

        .progress {
            background: var(--bg);
            border-radius: var(--radius-pill);
            overflow: hidden;
        }
        body.dark .progress {
            background: rgba(255,255,255,0.06);
        }

        .progress-bar {
            border-radius: var(--radius-pill);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ================================================
                   MOBILE BOTTOM NAV
                   ================================================ */

        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            padding-bottom: env(safe-area-inset-bottom, 0px);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 200;
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.06);
        }

        body.dark .bottom-nav {
            background: rgba(15, 23, 42, 0.92);
            border-color: rgba(51, 65, 85, 0.6);
        }

        .bottom-nav-inner {
            display: flex;
            height: 100%;
            align-items: stretch;
            padding: 0 8px;
        }

        .bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.62rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            transition: color 0.2s ease;
            position: relative;
            min-height: 44px;
            border-radius: var(--radius-sm);
        }

        .bottom-nav a:active {
            background: rgba(59, 130, 246, 0.08);
        }

        .bottom-nav a.active {
            color: var(--primary);
            font-weight: 700;
        }

        .bottom-nav a .nav-icon {
            font-size: 1.35rem;
            line-height: 1;
            margin-bottom: 1px;
        }

        .bottom-nav a .nav-label {
            font-size: 0.6rem;
        }

        .bottom-nav a.active .nav-icon {
            filter: drop-shadow(0 1px 3px rgba(59, 130, 246, 0.3));
        }

        /* Active indicator dot */
        .bottom-nav a.active::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
        }

        /* ================================================
                   RESPONSIVE
                   ================================================ */

        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 300;
                box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            }
            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 299;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }
            .sidebar-overlay.show {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .top-header {
                padding: 0 16px;
                height: 56px;
            }
            .top-header-left .hamburger {
                display: block;
            }
            .top-header-left .page-title {
                display: block;
            }

            .main-content {
                padding: 16px;
                padding-bottom: calc(var(--bottom-nav-height) + 16px);
            }

            .bottom-nav {
                display: block;
            }

            .kpi-card .kpi-value {
                font-size: 1.4rem;
            }

            .card-body {
                padding: 16px;
            }
            .card-header {
                padding: 14px 16px;
            }

            .table thead th {
                padding: 10px 12px;
                font-size: 0.65rem;
            }
            .table td {
                padding: 10px 12px;
                font-size: 0.82rem;
            }

            .modal-dialog {
                margin: 0;
                min-height: 100dvh;
                display: flex;
                align-items: flex-end;
            }
            .modal-content {
                border-radius: var(--radius-lg) var(--radius-lg) 0 0;
                max-height: 85dvh;
            }
            .modal.fade .modal-dialog {
                transform: translateY(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .modal.show .modal-dialog {
                transform: translateY(0);
            }

            .alert-flash {
                left: 16px;
                right: 16px;
                max-width: none;
                min-width: 0;
            }
        }

        @media (min-width: 768px) {
            .mobile-only { display: none; }
            .sidebar-overlay { display: none !important; }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .main-content {
                padding: 20px;
                padding-bottom: calc(20px + var(--bottom-nav-height));
            }
        }

        /* ================================================
                   DARK THEME TRANSITION
                   ================================================ */

        body.dark,
        body.dark .top-header,
        body.dark .card,
        body.dark .card-header,
        body.dark .card-body,
        body.dark .card-footer,
        body.dark .form-control,
        body.dark .form-select,
        body.dark .bottom-nav,
        body.dark .modal-content,
        body.dark .modal-header,
        body.dark .modal-footer,
        body.dark .table td,
        body.dark .table th {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Print styles */
        @media print {
            .sidebar,
            .top-header,
            .bottom-nav,
            .btn,
            .alert-flash {
                display: none !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .main-content {
                padding: 0 !important;
                animation: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
        }
    </style>
    <script>
        (function(){
            var s = localStorage.getItem('clubops-theme');
            if (!s && window.matchMedia('(prefers-color-scheme:dark)').matches) { s = 'dark'; localStorage.setItem('clubops-theme', 'dark'); }
            if (s === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.addEventListener('DOMContentLoaded', function(){ document.body.classList.add('dark'); });
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="app-layout">
        <!-- ================================================
        DESKTOP SIDEBAR
        ================================================ -->
        <aside class="sidebar" id="sidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}">
                    <span class="brand-icon">♠</span>
                    <div>
                        ClubOps
                        <small>OS</small>
                    </div>
                </a>
                <div class="brand-subtitle">Club Management System</div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="sidebar-section-label">Main</div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('players.index') }}" class="{{ request()->routeIs('players.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Players</span>
                </a>
                @if(\App\ClubOpsEdition::isPro() && !auth()->user()->club?->single_club)
                <a href="{{ route('invitations.index') }}" class="{{ request()->routeIs('teams*', 'invitations*') ? 'active' : '' }}">
                    <span class="nav-icon">👤</span>
                    <span class="nav-text">Team</span>
                </a>
                @endif
                <a href="{{ route('agents.index') }}" class="{{ request()->routeIs('agents.*') ? 'active' : '' }}">
                    <span class="nav-icon">🤝</span>
                    <span class="nav-text">Agents</span>
                </a>

                <hr class="sidebar-divider">
                <div class="sidebar-section-label">Finance</div>
                <a href="{{ route('ledger.entries.index') }}" class="{{ request()->routeIs('ledger.*') ? 'active' : '' }}">
                    <span class="nav-icon">💰</span>
                    <span class="nav-text">Ledger</span>
                </a>
                <a href="{{ route('reconciliations.index') }}" class="{{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">
                    <span class="nav-icon">✅</span>
                    <span class="nav-text">Reconciliation</span>
                </a>

                <hr class="sidebar-divider">
                <div class="sidebar-section-label">Operations</div>
                <a href="{{ route('promotions.index') }}" class="{{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎁</span>
                    <span class="nav-text">Promotions</span>
                </a>
                <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎫</span>
                    <span class="nav-text">Tickets</span>
                </a>
                <a href="{{ route('imports.index') }}" class="{{ request()->routeIs('imports.*') ? 'active' : '' }}">
                    <span class="nav-icon">📥</span>
                    <span class="nav-text">Imports</span>
                </a>

                <hr class="sidebar-divider">
                <div class="sidebar-section-label">Reports &amp; Admin</div>
                <a href="{{ route('reports.player-statement', 1) }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span>
                    <span class="nav-text">Reports</span>
                </a>
                <a href="{{ route('compliance.index') }}" class="{{ request()->routeIs('compliance.*') ? 'active' : '' }}">
                    <span class="nav-icon">🔒</span>
                    <span class="nav-text">Compliance</span>
                </a>
                <a href="{{ route('audit-log') }}" class="{{ request()->routeIs('audit-log') ? 'active' : '' }}">
                    <span class="nav-icon">📜</span>
                    <span class="nav-text">Audit Log</span>
                </a>
                <a href="{{ route('games.index') }}" class="{{ request()->routeIs('games.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎮</span>
                    <span class="nav-text">Games</span>
                </a>
                <a href="{{ route('billing.index') }}" class="{{ request()->routeIs('billing.*') ? 'active' : '' }}">
                    <span class="nav-icon">💳</span>
                    <span class="nav-text">Subscription</span>
                </a>
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <span class="nav-icon">⚙️</span>
                    <span class="nav-text">Settings</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="user-role">{{ auth()->user()->role->value ?? '' }}</div>
                    </div>
                </div>
                <div class="dark-mode-toggle" onclick="toggleDarkMode()">
                    <div class="toggle-track">
                        <span class="toggle-thumb">☀</span>
                    </div>
                    <span>Dark Mode</span>
                </div>
                <div style="margin-top:8px;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Sign Out</button>
                    </form>
                </div>
                <div style="margin-top:8px; display:flex; align-items:center; gap:6px; font-size:.68rem; color:#6ee7b7;">
                    🔒 E2E Encrypted — {{ \App\ClubOpsEdition::label() }}
                </div>
            </div>
        </aside>

        <!-- ================================================
        MAIN WRAPPER (Header + Content)
        ================================================ -->
        <div class="main-wrapper">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="top-header-left">
                    <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle sidebar">☰</button>
                    <span class="page-title">@yield('title', 'Dashboard')</span>
                </div>
                <div class="top-header-right">
                    <button class="header-btn" title="Notifications">
                        🔔
                        <span class="badge-dot"></span>
                    </button>
                    <div class="user-dropdown" onclick="event.target.closest('.user-dropdown')?.querySelector('form')?.submit()">
                        <div class="avatar-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="dropdown-name d-none d-md-inline">{{ auth()->user()->name ?? 'User' }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-flash" role="alert">
                        <span class="alert-icon">✅</span>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-flash" role="alert">
                        <span class="alert-icon">❌</span>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- ================================================
    MOBILE BOTTOM NAV
    ================================================ -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span class="nav-label">Home</span>
            </a>
            <a href="{{ route('players.index') }}" class="{{ request()->routeIs('players.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span class="nav-label">Players</span>
            </a>
            <a href="{{ route('ledger.entries.index') }}" class="{{ request()->routeIs('ledger.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span>
                <span class="nav-label">Ledger</span>
            </a>
            <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                <span class="nav-icon">🎫</span>
                <span class="nav-label">Tickets</span>
            </a>
            @if(\App\ClubOpsEdition::isPro() && !auth()->user()->club?->single_club)
            <a href="{{ route('invitations.index') }}" class="{{ request()->routeIs('invitations*', 'teams*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Team</span>
            </a>
            @endif
            <a href="{{ route('billing.index') }}" class="{{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <span class="nav-icon">💳</span>
                <span class="nav-label">Billing</span>
            </a>
            <a href="{{ route('compliance.index') }}" class="{{ request()->routeIs('compliance.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                <span class="nav-label">More</span>
            </a>
        </div>
    </nav>

    <!-- ================================================
    SCRIPTS
    ================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.setItem('clubops-theme', isDark ? 'dark' : 'light');

            // Update toggle thumb
            const thumb = document.querySelector('.toggle-thumb');
            if (thumb) thumb.textContent = isDark ? '🌙' : '☀';
        }

        // Update toggle thumb on load
        document.addEventListener('DOMContentLoaded', function() {
            const thumb = document.querySelector('.toggle-thumb');
            if (thumb) {
                thumb.textContent = document.body.classList.contains('dark') ? '🌙' : '☀';
            }
        });

        // --- Sidebar Toggle (Mobile) ---
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        // Close sidebar on route change (mobile)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
        });

        // --- Auto-dismiss Flash Messages ---
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert-flash').forEach(function(el) {
                setTimeout(function() {
                    el.style.animation = 'slideOutRight 0.3s ease forwards';
                    setTimeout(function() { el.remove(); }, 350);
                }, 5000);
            });
        });

        // --- User Dropdown (click to logout) ---
        document.addEventListener('DOMContentLoaded', function() {
            // Already handled inline via onclick
        });

        // --- Bootstrap modal enhancement for mobile (bottom sheet style) ---
        // The CSS already handles the transform for mobile modals
    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        /* =========================================
           2026 DESIGN SYSTEM (DARK MODE DEFAULT)
        ========================================= */
        :root {
            --bg-base: #030712;
            --bg-surface: rgba(15, 23, 42, 0.6);
            --border-glass: rgba(255, 255, 255, 0.05);
            --accent-glow: #06b6d4;
            --accent-glow-soft: rgba(6, 182, 212, 0.15);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --shadow-color: rgba(0, 0, 0, 0.5);
            --child-bg: rgba(15, 23, 42, 0.6);
            --child-text: #f1f5f9;
            --child-text-sec: #94a3b8;
            --child-border: rgba(255, 255, 255, 0.05);
        }

        /* =========================================
           LIGHT MODE VARIABLES
        ========================================= */
        .light-mode {
            --bg-base: #f1f5f9;
            --bg-surface: rgba(255, 255, 255, 0.8);
            --border-glass: rgba(0, 0, 0, 0.08);
            --accent-glow: #0891b2;
            --accent-glow-soft: rgba(8, 145, 178, 0.1);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --shadow-color: rgba(0, 0, 0, 0.08);
            --child-bg: #ffffff;
            --child-text: #111827;
            --child-text-sec: #6b7280;
            --child-border: #e2e8f0;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            min-height: 100%;
            background: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* --- Dark/Light Mode Hack for Child Views --- */
        /* =====================================================
   GLOBAL THEME SUPPORT FOR ALL CHILD PAGES
===================================================== */

        .bg-white,
        .bg-gray-50,
        .bg-gray-100,
        .bg-gray-200,
        .bg-gray-300,
        [class*="bg-white"],
        [class*="bg-gray"] {
            background: var(--child-bg) !important;
            color: var(--child-text) !important;
            border-color: var(--child-border) !important;
            transition: .3s ease;
        }

        .text-black,
        .text-white,
        .text-gray-900,
        .text-gray-800,
        .text-gray-700,
        .text-gray-600 {
            color: var(--child-text) !important;
        }

        .text-gray-500,
        .text-gray-400 {
            color: var(--child-text-sec) !important;
        }

        .border,
        .border-gray-100,
        .border-gray-200,
        .border-gray-300,
        .border-gray-400,
        [class*="border-gray"] {
            border-color: var(--child-border) !important;
        }

        .shadow,
        .shadow-sm,
        .shadow-md,
        .shadow-lg,
        .shadow-xl {
            box-shadow: 0 15px 35px var(--shadow-color) !important;
        }

        /* Dashboard cards */

        .rounded,
        .rounded-md,
        .rounded-lg,
        .rounded-xl,
        .rounded-2xl {
            background: var(--child-bg);
        }

        /* Tables */

        table {
            background: transparent !important;
            color: var(--child-text) !important;
        }

        thead {
            background: rgba(255, 255, 255, .05) !important;
        }

        .light-mode thead {
            background: #f3f4f6 !important;
        }

        tbody tr {
            background: transparent !important;
            border-color: var(--child-border) !important;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, .04) !important;
        }

        th,
        td {
            color: var(--child-text) !important;
        }

        /* Forms */

        input,
        select,
        textarea {
            background: var(--child-bg) !important;
            color: var(--child-text) !important;
            border: 1px solid var(--child-border) !important;
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--child-text-sec) !important;
        }

        /* Buttons */

        button {
            color: inherit;
        }

        /* SVG */

        svg {
            color: inherit !important;
        }

        /* Charts */

        canvas {
            filter: brightness(.95);
        }

        .light-mode canvas {
            filter: none;
        }

        /* --- Layout --- */
        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* --- Utility Classes --- */
        .flex {
            display: flex;
        }

        .inline-flex {
            display: inline-flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-center {
            justify-content: center;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .hidden {
            display: none !important;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .h-screen,
        .min-h-screen {
            min-height: 100vh;
            height: 100vh;
        }

        .h-16 {
            height: 4rem;
        }

        .w-full {
            width: 100%;
        }

        .w-5 {
            width: 1.25rem;
        }

        .p-4 {
            padding: 1rem;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .rounded {
            border-radius: 0.5rem;
        }

        .rounded-md {
            border-radius: 0.75rem;
        }

        .rounded-lg {
            border-radius: 1rem;
        }

        .rounded-xl {
            border-radius: 1.25rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .relative {
            position: relative;
        }

        .z-40 {
            z-index: 40;
        }

        .space-y-1> :not([hidden])~ :not([hidden]) {
            margin-top: 0.25rem;
        }

        .space-x-4>*+* {
            margin-left: 1rem;
        }

        /* =========================================
           NEW 2026 COMPONENTS
        ========================================= */

        .sidebar-modern {
            position: fixed;
            top: 16px;
            left: 16px;
            bottom: 16px;
            width: 280px;
            background: var(--bg-surface);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.3s ease;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
        }

        .sidebar-logo {
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--border-glass);
        }

        .sidebar-logo a {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-glow), #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px var(--accent-glow-soft);
        }

        .logo-icon i {
            color: white;
            -webkit-text-fill-color: white;
            font-size: 18px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(128, 128, 128, 0.2) transparent;
        }

        .nav-group-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            padding: 16px 12px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            background: var(--accent-glow-soft);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--accent-glow-soft);
            color: var(--accent-glow);
            box-shadow: 0 0 20px var(--accent-glow-soft);
        }

        .main-wrapper {
            margin-left: 312px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .header-modern {
            position: sticky;
            top: 0;
            z-index: 40;
            margin: 16px 16px 0;
            padding: 12px 24px;
            background: var(--bg-surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
            margin-right: 16px;
        }

        .header-search {
            position: relative;
            max-width: 400px;
            flex: 1;
        }

        .header-search input {
            width: 100%;
            background: var(--accent-glow-soft);
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            padding: 10px 16px 10px 42px;
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .header-search input::placeholder {
            color: var(--text-secondary);
        }

        .header-search input:focus {
            border-color: var(--accent-glow);
            box-shadow: 0 0 0 3px var(--accent-glow-soft);
        }

        .header-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border-glass);
            background: var(--accent-glow-soft);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .icon-btn:hover {
            background: var(--border-glass);
            color: var(--accent-glow);
            transform: scale(1.05);
        }

        .notif-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.5);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin-left: 8px;
            cursor: pointer;
        }

        /* User Menu Dropdown */
        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 12px;
            transition: background 0.2s ease;
        }

        .user-menu-btn:hover {
            background: var(--accent-glow-soft);
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            width: 220px;
            background: var(--bg-surface);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            padding: 8px 0;
            z-index: 60;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .user-dropdown.is-active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            font-size: 14px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .dropdown-link:hover {
            background: var(--accent-glow-soft);
            color: var(--text-primary);
            padding-left: 20px;
        }

        .dropdown-link i {
            width: 16px;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-glass);
            margin: 6px 0;
        }

        .dropdown-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 16px;
            font-size: 14px;
            color: #f87171;
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
        }

        .dropdown-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            padding-left: 20px;
        }

        .content-modern {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(128, 128, 128, 0.2) transparent;
        }

        .alert-modern {
            padding: 16px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .light-mode .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: #047857;
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 45;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .sidebar-modern {
                transform: translateX(-120%);
            }

            .sidebar-modern.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .header-search {
                display: none;
            }

            /* Fix dropdown position on mobile */
            #notif-dropdown {
                left: auto !important;
                right: 0 !important;
            }
        }
        .profile-dropdown-wrapper{
    position:relative;
    display:inline-block;
}

.user-avatar{
    width:42px;
    height:42px;
    border-radius:12px;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    cursor:pointer;
    user-select:none;
}

.profile-dropdown{
    position:absolute;
    top:55px;
    right:0;
    width:260px;
    background:#1e293b;
    border-radius:14px;
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 15px 40px rgba(0,0,0,.35);
    display:none;
    overflow:hidden;
    z-index:99999;
}

.profile-dropdown.show{
    display:block;
}

.profile-header{
    display:flex;
    align-items:center;
    gap:14px;
    padding:18px;
}

.profile-avatar{
    width:48px;
    height:48px;
    border-radius:12px;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-weight:700;
}

.profile-info h5{
    margin:0;
    color:#fff;
    font-size:15px;
}

.profile-info p{
    margin:4px 0 0;
    color:#94a3b8;
    font-size:13px;
}

.dropdown-divider{
    height:1px;
    background:#334155;
}

.dropdown-logout{
    width:100%;
    background:none;
    border:none;
    color:#ef4444;
    padding:15px 18px;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
    font-size:14px;
    transition:.2s;
}

.dropdown-logout:hover{
    background:#293548;
    color:#fff;
}
/* ================= LIGHT MODE ================= */

.light-mode .profile-dropdown{

    background:#ffffff;

    border:1px solid #E5E7EB;

    box-shadow:
        0 12px 35px rgba(0,0,0,.12);

}

.light-mode .profile-info h5{

    color:#111827;

}

.light-mode .profile-info p{

    color:#6B7280;

}

.light-mode .dropdown-divider{

    background:#ECEFF3;

}

.light-mode .dropdown-logout{

    color:#dc2626;

}

.light-mode .dropdown-logout:hover{

    background:#F3F4F6;

    color:#111827;

}

/* ================= Animation ================= */

@keyframes dropdown{

    from{

        opacity:0;

        transform:translateY(10px);

    }

    to{

        opacity:1;

        transform:translateY(0);

    }

}
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Floating Sidebar -->
    <aside id="sidebar" class="sidebar-modern">
        @php
            $settings = \App\Models\Setting::pluck('value', 'key');
        @endphp

        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}">
                @if (setting('site_logo'))
                    <img src="{{ asset(setting('site_logo')) }}" alt="{{ setting('site_name') }}"
                        style="height: 40px; width: 40px; object-fit: contain; border-radius: 12px;">
                @else
                    <div class="logo-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                @endif

                <span>{{ setting('site_name', 'Admin') }}</span>
            </a>
        </div>

        <nav class="sidebar-nav">

            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-grid-2"></i> Dashboard
            </a>

            <div class="nav-group-title">Management</div>

            <a href="{{ route('admin.businesses.index') }}"
                class="nav-link {{ request()->routeIs('admin.businesses.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Businesses
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>

            <div class="nav-group-title">Finance</div>

            <a href="{{ route('admin.plans.index') }}"
                class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Plans
            </a>

            <a href="{{ route('admin.payments.index') }}"
                class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Payments
            </a>

            <a href="{{ route('admin.coupons.index') }}"
                class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-percent"></i> Coupons
            </a>

            <div class="nav-group-title">System</div>

            <a href="{{ route('admin.tickets.index') }}"
                class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i> Tickets
            </a>

            <a href="{{ route('admin.subscriptions.index') }}"
                class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Subscriptions
            </a>

            <a href="{{ route('admin.ai-credits.index') }}"
                class="nav-link {{ request()->routeIs('admin.ai-credits.*') ? 'active' : '' }}">
                <i class="fas fa-robot"></i> AI Credits
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-gear"></i> Settings
            </a>

        </nav>

        <!-- Admin Profile pinned to bottom -->

    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">

        <!-- Frosted Glass Header -->
        <header class="header-modern">
            <div class="flex items-center">
                <button id="menu-btn" class="mobile-menu-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search anything... (⌘K)">
                </div>
            </div>

            <div class="header-actions">
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="icon-btn" title="Toggle Light/Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- ✅ ADMIN NOTIFICATION DROPDOWN START -->
                <div class="relative" id="notif-menu">
                    <button class="icon-btn" id="notif-menu-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        @php 
                                $adminId = auth()->id();
                            $unreadCount = \App\Models\Notification::where('user_id', $adminId)->where('is_read', false)->count(); 
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notif-dot"></span>
                        @endif
                    </button>

                    <div id="notif-dropdown" class="user-dropdown" style="width: 340px; right: 0; left: auto;">
                        <div
                            style="padding: 12px 16px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600; font-size: 14px; color: var(--text-primary);">
                                Notifications <span
                                    style="color: var(--accent-glow); font-size: 12px;">({{ $unreadCount }})</span>
                            </span>
                            <button onclick="markAllReadHeader()"
                                style="background:none; border:none; color: var(--accent-glow); font-size: 12px; cursor: pointer; font-weight: 500;">Mark
                                all read</button>
                        </div>

                        <div style="max-height: 350px; overflow-y: auto;" class="sidebar-nav">
                            @php
                                $notifications = \App\Models\Notification::where('user_id', $adminId)
                                    ->where('is_read', false)
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @if($notifications->count() > 0)
                                @foreach($notifications as $notif)
                                    @php
                                        $icon = 'fas fa-info-circle';
                                        $color = 'var(--accent-glow)';
                                        if ($notif->type == 'business') {
                                            $icon = 'fas fa-building';
                                            $color = '#818cf8';
                                        } elseif ($notif->type == 'ticket') {
                                            $icon = 'fas fa-headset';
                                            $color = '#f59e0b';
                                        } elseif ($notif->type == 'payment') {
                                            $icon = 'fas fa-credit-card';
                                            $color = '#22c55e';
                                        } elseif ($notif->type == 'alert') {
                                            $icon = 'fas fa-exclamation-triangle';
                                            $color = '#ef4444';
                                        }
                                    @endphp
                                    <a href="{{ $notif->data['action_url'] ?? '#' }}" class="dropdown-link"
                                        style="align-items: flex-start; padding: 12px 16px;">
                                        <div
                                            style="width: 32px; height: 32px; border-radius: 8px; background: {{ $color }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                                            <i class="{{ $icon }}" style="font-size: 12px; color: {{ $color }};"></i>
                                        </div>
                                        <div style="margin-left: 4px;">
                                            <p
                                                style="font-size: 13px; color: var(--text-primary); line-height: 1.4; font-weight: 500;">
                                                {{ $notif->title }}</p>
                                            <p style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                                                {{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now' }}</p>
                                        </div>
                                    </a>
                                @endforeach
                                <a href="{{ route('notifications.index') }}"
                                    style="display:block; padding: 12px; text-align:center; font-size: 12px; color: var(--accent-glow); border-top: 1px solid var(--border-glass); font-weight: 600;">
                                    View all notifications
                                </a>
                            @else
                                <div
                                    style="padding: 24px 16px; text-align: center; color: var(--text-secondary); font-size: 13px;">
                                    <i class="fas fa-bell-slash"
                                        style="font-size: 20px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                    You're all caught up!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- ✅ ADMIN NOTIFICATION DROPDOWN END -->

                <!-- Admin Profile Avatar -->
                <div class="profile-dropdown-wrapper">

    <div class="user-avatar" id="profileBtn">
        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
    </div>

    <div class="profile-dropdown" id="profileDropdown">

        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>

            <div class="profile-info">
                <h5>{{ Auth::user()->name }}</h5>
                <p>{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="dropdown-divider"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>

    </div>

</div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content-modern">
            @if (session('success'))
                <div class="alert-modern alert-success">
                    <span><i class="fas fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // --- Mobile Sidebar Toggle ---
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        // --- Notification Dropdown Toggle ---
        const notifMenuBtn = document.getElementById('notif-menu-btn');
        const notifDropdown = document.getElementById('notif-dropdown');

        notifMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('is-active');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#notif-menu')) {
                notifDropdown?.classList.remove('is-active');
            }
        });

        // --- Mark All Read (Header) ---
        function markAllReadHeader() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.success) location.reload();
            });
        }

        // --- Theme Toggle Logic ---
        const themeToggleBtn = document.getElementById('theme-toggle');
        const body = document.body;
        const themeIcon = themeToggleBtn.querySelector('i');

        // Check localStorage on load
        // Load saved theme
        const savedTheme = localStorage.getItem('admin_theme');

        if (savedTheme === 'light') {
            enableLightMode();
        } else {
            enableDarkMode();
        }

        themeToggleBtn.addEventListener('click', () => {
            if (body.classList.contains('light-mode')) {
                enableDarkMode();
            } else {
                enableLightMode();
            }
        });

        function enableLightMode() {
            body.classList.add('light-mode');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
            localStorage.setItem('admin_theme', 'light');
        }

        function enableDarkMode() {
            body.classList.remove('light-mode');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
            localStorage.setItem('admin_theme', 'dark');
        }

        // --- Keyboard Shortcut for Search ---
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('.header-search input');
                if (searchInput) searchInput.focus();
            }
        });
    </script>
    <script>
const profileBtn = document.getElementById("profileBtn");
const profileDropdown = document.getElementById("profileDropdown");

profileBtn.addEventListener("click", function (e) {
    e.stopPropagation();
    profileDropdown.classList.toggle("show");
});

document.addEventListener("click", function () {
    profileDropdown.classList.remove("show");
});

profileDropdown.addEventListener("click", function (e) {
    e.stopPropagation();
});

</script>
    @stack('scripts')
</body>

</html>
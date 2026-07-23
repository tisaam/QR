<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; }
        body {
            background: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .bg-white {
            background: var(--child-bg) !important;
            border: 1px solid var(--child-border) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: var(--child-text) !important;
            transition: all 0.3s ease;
        }
        .bg-gray-100 { background: transparent !important; }
        .text-gray-800, .text-gray-700 { color: var(--child-text) !important; }
        .text-gray-500 { color: var(--child-text-sec) !important; }
        .shadow-md, .shadow-lg, .shadow-sm { box-shadow: 0 25px 50px -12px var(--shadow-color) !important; }
        .border, [class*="border-"] { border-color: var(--child-border) !important; }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; color: inherit; }
        img { max-width: 100%; display: block; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-1 { flex: 1 1 0%; }
        .overflow-hidden { overflow: hidden; }
        .overflow-y-auto { overflow-y: auto; }
        .h-screen, .min-h-screen { height: 100vh; min-height: 100vh; }
        .relative { position: relative; }
        .z-40 { z-index: 40; }
        .hidden { display: none !important; }
        .mb-4 { margin-bottom: 1rem; }

        .sidebar-modern {
            position: fixed;
            top: 16px; left: 16px; bottom: 16px;
            width: 280px;
            background: var(--bg-surface);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            display: flex; flex-direction: column;
            z-index: 50;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.3s ease;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
        }

        .sidebar-logo { padding: 24px 24px 20px; border-bottom: 1px solid var(--border-glass); }
        .sidebar-logo a {
            display: flex; align-items: center; gap: 12px;
            font-size: 1.25rem; font-weight: 700;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent-glow), #8b5cf6);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 20px var(--accent-glow-soft);
        }
        .logo-icon i { color: white; -webkit-text-fill-color: white; font-size: 18px; }

        .sidebar-nav {
            flex: 1; padding: 16px; overflow-y: auto;
            scrollbar-width: thin; scrollbar-color: rgba(128,128,128,0.2) transparent;
        }
        .nav-group-title { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); padding: 16px 12px 8px; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; border-radius: 12px;
            color: var(--text-secondary); font-size: 14px; font-weight: 500;
            transition: all 0.2s ease; margin-bottom: 4px;
        }
        .nav-link i { width: 20px; text-align: center; font-size: 16px; transition: color 0.2s ease; }
        .nav-link:hover { background: var(--accent-glow-soft); color: var(--text-primary); }
        .nav-link.active { background: var(--accent-glow-soft); color: var(--accent-glow); box-shadow: 0 0 20px var(--accent-glow-soft); }
        .nav-link.plan-link { color: var(--accent-glow); }
        .nav-link.plan-link i { color: #eab308; }

        .main-wrapper {
            margin-left: 312px; height: 100vh; display: flex; flex-direction: column;
            transition: margin 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .header-modern {
            position: sticky; top: 0; z-index: 40;
            margin: 16px 16px 0; padding: 12px 24px;
            background: var(--bg-surface); backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass); border-radius: 16px;
            display: flex; align-items: center; justify-content: space-between;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .mobile-menu-btn { display: none; background: none; border: none; color: var(--text-primary); font-size: 20px; cursor: pointer; margin-right: 16px; }
        .header-actions { display: flex; align-items: center; gap: 8px; }
        
       .icon-btn {
    width: 40px; height: 40px; border-radius: 10px;
    border: 1px solid var(--border-glass); background: var(--accent-glow-soft);
    color: var(--text-secondary); display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative; overflow: visible !important; transition: all 0.3s ease;
}
.icon-btn:hover { background: var(--border-glass); color: var(--accent-glow); transform: scale(1.05); }

.notif-count {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 20px;
    height: 20px;
    background: #ef4444;
    color: #fff;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);
    border: 2px solid var(--bg-base);
    z-index: 9999;
}

@keyframes pulse-badge { 
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.5); } 
    70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } 
}
        .user-menu-btn { display: flex; align-items: center; gap: 10px; background: none; border: none; cursor: pointer; padding: 4px; border-radius: 12px; transition: background 0.2s ease; }
        .user-menu-btn:hover { background: var(--accent-glow-soft); }
        .user-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #2563eb); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3); }
        .user-name { font-size: 14px; font-weight: 500; color: var(--text-primary); }
        .chevron-icon { font-size: 10px; color: var(--text-secondary); transition: transform 0.3s ease; }

        .user-dropdown {
            position: absolute; right: 0; top: calc(100% + 12px); width: 220px;
            background: var(--bg-surface); backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass); border-radius: 16px;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            padding: 8px 0; z-index: 60;
            opacity: 0; visibility: hidden; transform: translateY(-10px) scale(0.95);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .user-dropdown.is-active { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .dropdown-link { display: flex; align-items: center; gap: 12px; padding: 10px 16px; font-size: 14px; color: var(--text-secondary); text-decoration: none; transition: all 0.15s ease; }
        .dropdown-link:hover { background: var(--accent-glow-soft); color: var(--text-primary); padding-left: 20px; }
        .dropdown-link i { width: 16px; text-align: center; }
        .dropdown-divider { height: 1px; background: var(--border-glass); margin: 6px 0; }
        .dropdown-logout { display: flex; align-items: center; gap: 12px; width: 100%; padding: 10px 16px; font-size: 14px; color: #f87171; background: transparent; border: none; cursor: pointer; text-align: left; transition: all 0.15s ease; }
        .dropdown-logout:hover { background: rgba(239, 68, 68, 0.1); padding-left: 20px; }

        .content-modern { flex: 1; padding: 24px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(128,128,128,0.2) transparent; }

        .alert-modern { padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; font-size: 14px; font-weight: 500; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; }
        .light-mode .alert-success { color: #047857; background: rgba(16, 185, 129, 0.15); }
        .light-mode .alert-error { color: #b91c1c; background: rgba(239, 68, 68, 0.1); }
        .alert-close { background: none; border: none; color: inherit; cursor: pointer; opacity: 0.7; }
        .alert-close:hover { opacity: 1; }

        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 45; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.show { opacity: 1; pointer-events: auto; }

        @media (max-width: 768px) {
            .sidebar-modern { transform: translateX(-120%); }
            .sidebar-modern.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
            .user-name { display: none; }
            /* Fix dropdown position on mobile */
            #notif-dropdown { left: auto !important; right: 0 !important; }
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased">

    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="sidebar-modern">
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}">
                <div class="logo-icon"><i class="fas fa-qrcode"></i></div>
                QR-Review
            </a>
        </div>

       <nav class="sidebar-nav">

    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <a href="{{ route('qr-codes.index') }}"
       class="nav-link {{ request()->routeIs('qr-codes.*') ? 'active' : '' }}">
        <i class="fas fa-qrcode"></i> QR Codes
    </a>

    <a href="{{ route('reviews.index') }}"
       class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
        <i class="fas fa-star"></i> Reviews
    </a>

    <a href="{{ route('analytics.index') }}"
       class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Analytics
    </a>

    <div class="nav-group-title">Campaigns</div>

    <a href="{{ route('whatsapp.index') }}"
       class="nav-link {{ request()->routeIs('whatsapp.*') ? 'active' : '' }}">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>

    <a href="{{ route('sms.index') }}"
       class="nav-link {{ request()->routeIs('sms.*') ? 'active' : '' }}">
        <i class="fas fa-sms"></i> SMS
    </a>

    <div class="nav-group-title">Management</div>

    <a href="{{ route('subscription.current') }}"
       class="nav-link plan-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}">
        <i class="fas fa-crown"></i> My Plan
    </a>

    <a href="{{ route('branches.index') }}"
       class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
        <i class="fas fa-code-branch"></i> Branches
    </a>

    <a href="{{ route('nfc-cards.index') }}"
       class="nav-link {{ request()->routeIs('nfc-cards.*') ? 'active' : '' }}">
        <i class="fas fa-wifi"></i> NFC Cards
    </a>

</nav>
    </aside>

    <div class="main-wrapper">
        <header class="header-modern">
            <div class="flex items-center">
                <button id="menu-btn" class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            </div>

            <div class="header-actions">
                <button id="theme-toggle" class="icon-btn" title="Toggle Light/Dark Mode"><i class="fas fa-moon"></i></button>

                <!-- ✅ NEW NOTIFICATION DROPDOWN START -->
               <div class="relative" id="notif-menu">
    <button class="icon-btn" id="notif-menu-btn" title="Notifications" style="position: relative !important; overflow: visible !important;">
        <i class="fas fa-bell"></i>
        @php 
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); 
        @endphp
        @if($unreadCount > 0)
            <span class="notif-count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>
    
    <div id="notif-dropdown" class="user-dropdown" style="width: 340px; right: 0; left: auto; z-index: 999;">
        <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 600; font-size: 14px; color: var(--text-primary);">
                Notifications <span style="color: var(--accent-glow); font-size: 12px;">({{ $unreadCount }})</span>
            </span>
            <button onclick="markAllReadHeader()" style="background:none; border:none; color: var(--accent-glow); font-size: 12px; cursor: pointer; font-weight: 500;">Mark all read</button>
        </div>
        
        <div style="max-height: 350px; overflow-y: auto;" class="sidebar-nav">
            @php
                $notifications = \App\Models\Notification::where('user_id', auth()->id())
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
                        if($notif->type == 'review') { $icon = 'fas fa-star'; $color = '#eab308'; }
                        elseif($notif->type == 'qr_scan') { $icon = 'fas fa-qrcode'; $color = '#22c55e'; }
                        elseif($notif->type == 'whatsapp') { $icon = 'fab fa-whatsapp'; $color = '#22c55e'; }
                        elseif($notif->type == 'alert') { $icon = 'fas fa-exclamation-triangle'; $color = '#ef4444'; }
                        elseif($notif->type == 'admin_action') { $icon = 'fas fa-shield-halved'; $color = '#818cf8'; }
                    @endphp
                    <a href="{{ $notif->data['action_url'] ?? route('notifications.index') }}" class="dropdown-link" style="align-items: flex-start; padding: 12px 16px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $color }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <i class="{{ $icon }}" style="font-size: 12px; color: {{ $color }};"></i>
                        </div>
                        <div style="margin-left: 4px;">
                            <p style="font-size: 13px; color: var(--text-primary); line-height: 1.4; font-weight: 500;">{{ $notif->title }}</p>
                            <p style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now' }}</p>
                        </div>
                    </a>
                @endforeach
                <a href="{{ route('notifications.index') }}" style="display:block; padding: 12px; text-align:center; font-size: 12px; color: var(--accent-glow); border-top: 1px solid var(--border-glass); font-weight: 600;">
                    View all notifications
                </a>
            @else
                <div style="padding: 24px 16px; text-align: center; color: var(--text-secondary); font-size: 13px;">
                    <i class="fas fa-bell-slash" style="font-size: 20px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                    You're all caught up!
                </div>
            @endif
        </div>
    </div>
</div>
                <!-- ✅ NEW NOTIFICATION DROPDOWN END -->

                @auth
                    <div class="relative" id="user-menu">
                        <button class="user-menu-btn" id="user-menu-btn">
                            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down chevron-icon"></i>
                        </button>
                        <div id="user-dropdown" class="user-dropdown">
                            <a href="{{ route('subscription.current') }}" class="dropdown-link"><i class="fas fa-crown" style="color: #eab308;"></i> My Plan</a>
                            <a href="{{ route('settings.index') }}" class="dropdown-link"><i class="fas fa-cog"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <a href="{{ route('login') }}" style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary);">Login</a>
                        <a href="{{ route('register') }}" style="padding: 0.5rem 1rem; background: var(--accent-glow); color: white; font-size: 0.875rem; font-weight: 600; border-radius: 0.75rem;">Sign Up</a>
                    </div>
                @endauth
            </div>
        </header>

        <main class="content-modern">
            @if(session('success'))
                <div class="alert-modern alert-success">
                    <span><i class="fas fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-modern alert-error">
                    <span><i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        // --- User Dropdown Toggle ---
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('is-active');
            const chevron = userMenuBtn.querySelector('.chevron-icon');
            chevron.style.transform = userDropdown.classList.contains('is-active') ? 'rotate(180deg)' : 'rotate(0deg)';
            // Close notif dropdown if open
            notifDropdown?.classList.remove('is-active');
        });
        
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#user-menu')) {
                userDropdown?.classList.remove('is-active');
                const chevron = userMenuBtn?.querySelector('.chevron-icon');
                if(chevron) chevron.style.transform = 'rotate(0deg)';
            }
        });

        // --- Notification Dropdown Toggle ---
        const notifMenuBtn = document.getElementById('notif-menu-btn');
        const notifDropdown = document.getElementById('notif-dropdown');

        notifMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('is-active');
            // Close user dropdown if open
            userDropdown?.classList.remove('is-active');
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
                if(data.success) location.reload();
            });
        }

        // --- Theme Toggle Logic ---
        const themeToggleBtn = document.getElementById('theme-toggle');
        const body = document.body;
        const themeIcon = themeToggleBtn.querySelector('i');

        const savedTheme = localStorage.getItem('user_theme') || 'dark';

if (savedTheme === 'light') {
    enableLightMode();
} else {
    enableDarkMode();
}

        themeToggleBtn.addEventListener('click', () => {
            if (body.classList.contains('light-mode')) enableDarkMode();
            else enableLightMode();
        });

        function enableLightMode() {
    body.classList.add('light-mode');
    body.classList.remove('dark-mode');

    themeIcon.classList.remove('fa-moon');
    themeIcon.classList.add('fa-sun');

    localStorage.setItem('user_theme', 'light');
}

function enableDarkMode() {
    body.classList.remove('light-mode');
    body.classList.add('dark-mode');

    themeIcon.classList.remove('fa-sun');
    themeIcon.classList.add('fa-moon');

    localStorage.setItem('user_theme', 'dark');
}
    </script>
    @stack('scripts')
</body>

</html>
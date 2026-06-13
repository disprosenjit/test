<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --sidebar-bg: rgba(17, 24, 39, 0.7);
            --card-bg: rgba(22, 30, 49, 0.6);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-color: #f3f4f6;
            --text-muted: #9ca3af;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --accent: #10b981;
            --danger: #ef4444;
            --glass-blur: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.12) 0px, transparent 50%);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(var(--glass-blur));
            border-right: 1px solid var(--card-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .sidebar {
            width: 80px;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            color: var(--primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--card-border);
            text-decoration: none;
        }

        .sidebar-brand i {
            font-size: 24px;
            color: var(--primary);
            filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.6));
        }

        .sidebar-brand span {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            flex: 1;
            padding: 16px 16px;
            list-style: none;
            overflow-y: auto;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-link:hover, .menu-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .menu-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0.05) 100%);
            border-left: 3px solid var(--primary);
        }

        .menu-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 80px;
        }

        /* Collapsed Sidebar Styles */
        body.sidebar-collapsed .sidebar,
        body.sidebar-collapsed .sidebar-menu {
            overflow: visible !important;
        }

        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .menu-link span,
        body.sidebar-collapsed .submenu-caret {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 24px 0;
        }

        body.sidebar-collapsed .menu-link {
            justify-content: center;
            padding: 8px 0;
            gap: 0;
        }

        /* Header */
        .header {
            height: 70px;
            border-bottom: 1px solid var(--card-border);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11, 15, 25, 0.5);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-title h2 {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #fff;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .logout-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }

        .visit-site-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .visit-site-btn:hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        /* Profile Dropdown styling */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-toggle:hover, .dropdown-toggle.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            min-width: 200px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            z-index: 300;
            padding: 8px 0;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-header {
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .dropdown-header .user-name {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        .dropdown-header .user-role {
            color: var(--text-muted);
            font-size: 12px;
            white-space: nowrap;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--card-border);
            margin: 6px 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Content Area */
        .content {
            padding: 40px;
            flex: 1;
            min-width: 0;
        }

        /* Card System */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(var(--glass-blur));
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-color);
            border: 1px solid var(--card-border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: var(--accent);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-muted);
            border: 1px solid var(--card-border);
        }
        
        .bg-white { background-color: var(--card-bg) !important; color: #fff !important; border-color: var(--card-border) !important; backdrop-filter: blur(var(--glass-blur)); }
        .text-slate-900 { color: #fff !important; }
        .text-slate-600 { color: var(--text-muted) !important; }
        .text-slate-500 { color: #6b7280 !important; }
        .bg-slate-50 { background-color: rgba(255, 255, 255, 0.05) !important; }
        .border-slate-200 { border-color: var(--card-border) !important; }
        .border-slate-100 { border-color: rgba(255, 255, 255, 0.05) !important; }
        .divide-slate-100 > :not([hidden]) ~ :not([hidden]) { border-color: rgba(255, 255, 255, 0.05) !important; }
        .hover\:bg-slate-50:hover { background-color: rgba(255, 255, 255, 0.08) !important; }

        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="url"], input[type="search"], input[type="tel"], textarea, select {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border-color: var(--card-border) !important;
            color: #fff !important;
        }
        input:focus, textarea:focus, select:focus {
            background-color: rgba(15, 23, 42, 0.9) !important;
            border-color: var(--primary) !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }

    </style>
    @stack('head')
</head>
<body>
    <script>
        (function() {
            const sidebarState = localStorage.getItem('sidebar-collapsed');
            if (sidebarState === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Admin Panel</span>
        </a>
        
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.properties.index') }}" class="menu-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building"></i>
                    <span>Properties</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.inquiries.index') }}" class="menu-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Inquiries</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.requests.index') }}" class="menu-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard"></i>
                    <span>Special Requests</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.investments.index') }}" class="menu-link {{ request()->routeIs('admin.investments.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Investments</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.themes.index') }}" class="menu-link {{ request()->routeIs('admin.themes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-paint-roller"></i>
                    <span>Themes</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-title" style="display: flex; align-items: center; gap: 16px;">
                <button type="button" class="sidebar-toggle" id="sidebar-toggle" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2>@yield('title', 'Dashboard')</h2>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 12px; position: relative;">
                <a href="{{ route('home') }}" target="_blank" class="visit-site-btn" title="Visit Site">
                    <i class="fa-solid fa-globe"></i>
                </a>
                
                <!-- Profile Dropdown Trigger -->
                <div class="dropdown" id="profile-dropdown">
                    <button type="button" class="dropdown-toggle" id="profile-dropdown-btn" title="User Profile">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdown-menu" id="profile-dropdown-menu">
                        <div class="dropdown-header">
                            <span class="user-name">{{ Auth::user()?->name ?? 'Admin User' }}</span>
                            <span class="user-role">{{ Auth::user()?->email ?? '' }}</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content">
            @if(session('success'))
                <div style="margin-bottom: 24px; padding: 16px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; color: #10b981; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="margin-bottom: 24px; padding: 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; color: #ef4444; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', isCollapsed);
                });
            }

            // Profile Dropdown Toggle
            const dropdownBtn = document.getElementById('profile-dropdown-btn');
            const dropdownMenu = document.getElementById('profile-dropdown-menu');
            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                    dropdownBtn.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownBtn.classList.remove('active');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

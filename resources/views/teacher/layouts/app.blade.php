<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Azhary Academy Teacher</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #e2e8f0;
            color: #0f172a;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d9488 0%, #064e3b 100%);
            color: #f0fdfa;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.25);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-logo-text,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed .menu-section-title,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .logout-btn span,
        .sidebar.collapsed .language-switcher {
            opacity: 0;
            visibility: hidden;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 12px;
        }

        .sidebar.collapsed .menu-item i {
            margin-right: 0;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 24px 12px;
        }

        .sidebar.collapsed .sidebar-logo {
            justify-content: center;
        }

        .sidebar.collapsed .user-profile {
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-footer {
            padding: 20px 12px;
        }

        .sidebar.collapsed .logout-btn {
            justify-content: center;
        }

        .sidebar.collapsed .logout-btn i {
            margin-right: 0;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #064e3b;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #2dd4bf;
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 2px solid rgba(45, 212, 191, 0.4);
            position: relative;
            transition: padding 0.3s ease;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 12px;
        }

        .sidebar-toggle {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(45, 212, 191, 0.35);
            border: 1px solid rgba(45, 212, 191, 0.6);
            color: #99f6e4;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 10;
        }

        .sidebar-toggle:hover {
            background: #14b8a6;
            color: #fff;
            border-color: #0d9488;
        }

        .sidebar.collapsed .sidebar-toggle {
            right: 50%;
            transform: translateX(50%);
            top: 24px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-right: 40px;
        }

        .sidebar.collapsed .sidebar-logo {
            padding-right: 0;
            opacity: 0;
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        .sidebar-logo i {
            font-size: 32px;
            color: #2dd4bf;
        }

        .sidebar-logo-text {
            flex: 1;
            min-width: 0;
        }

        .sidebar-logo-text h2 {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: #99f6e4;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 24px;
        }

        .menu-section-title {
            padding: 0 20px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #99f6e4;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ccfbf1;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(45, 212, 191, 0.25);
            color: #ffffff;
        }

        .menu-item.active {
            background: rgba(45, 212, 191, 0.35);
            color: #2dd4bf;
            border-left: 4px solid #14b8a6;
        }

        .menu-item.active i {
            color: #2dd4bf;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 2px solid rgba(45, 212, 191, 0.4);
            background: #064e3b;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: #fff;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .user-info p {
            font-size: 12px;
            color: #99f6e4;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background: rgba(239, 68, 68, 0.25);
            border: 2px solid #ef4444;
            color: #fca5a5;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        .top-navbar {
            background: #ffffff;
            padding: 16px 32px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0d9488;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .notification-icon:hover {
            background: #14b8a6;
            color: #fff;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 10px;
            height: 10px;
            background: #dc2626;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .content-area {
            padding: 32px;
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        /* Additional Utility Styles */
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.1);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(20, 184, 166, 0.5);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #a7f3d0;
            color: #15803d;
        }

        .badge-warning {
            background: #fef08a;
            color: #a16207;
        }

        .badge-info {
            background: #a5f3fc;
            color: #0e7490;
        }
        
        .language-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: rgba(45, 212, 191, 0.2);
            border: 1px solid rgba(45, 212, 191, 0.5);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .language-switcher:hover {
            background: rgba(45, 212, 191, 0.35);
        }
        
        .language-switcher select {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            outline: none;
        }
        
        .language-switcher select option {
            background: #064e3b;
            color: #fff;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="fas fa-bars" id="sidebarToggleIcon"></i>
                </button>
                <div class="sidebar-logo">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div class="sidebar-logo-text">
                        <h2>{{ __('teacher.academy_name') }}</h2>
                        <p>{{ __('teacher.teacher_portal') }}</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">{{ __('teacher.main_menu') }}</div>
                    <a href="{{ route('teacher.dashboard') }}" class="menu-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ __('teacher.dashboard') }}</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">{{ __('teacher.teaching') }}</div>
                    <a href="{{ route('teacher.courses') }}" class="menu-item {{ request()->routeIs('teacher.courses*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        <span>{{ __('teacher.courses') }}</span>
                    </a>
                    <a href="{{ route('teacher.timetable') }}" class="menu-item {{ request()->routeIs('teacher.timetable*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        <span>{{ __('teacher.timetable') }}</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">{{ __('teacher.management') }}</div>
                    <a href="{{ route('teacher.students') }}" class="menu-item {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>{{ __('teacher.students') }}</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('teacher')->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ Auth::guard('teacher')->user()->name }}</h4>
                        <p>{{ __('teacher.teacher') }}</p>
                    </div>
                </div>
                <!-- Language Switcher -->
                <form method="POST" action="{{ route('teacher.language.switch') }}" style="margin-bottom: 12px;">
                    @csrf
                    <div class="language-switcher">
                        <i class="fas fa-globe" style="color: #2dd4bf;"></i>
                        <select name="locale" onchange="this.form.submit()" style="width: 100%;">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>🇫🇷 FR</option>
                        </select>
                    </div>
                </form>
                <form method="POST" action="{{ route('teacher.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('teacher.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-navbar">
                <h1 class="page-title">@yield('page-title')</h1>
                <div class="top-navbar-right">
                    <!-- Language Switcher in Top Navbar -->
                    <form method="POST" action="{{ route('teacher.language.switch') }}" style="margin-right: 16px;">
                        @csrf
                        <div class="language-switcher" style="background: #ccfbf1; border: 2px solid #99f6e4; padding: 8px 12px; border-radius: 8px;">
                            <i class="fas fa-globe" style="color: #0d9488;"></i>
                            <select name="locale" onchange="this.form.submit()" style="background: transparent; border: none; color: #0f172a; font-size: 14px; font-weight: 600; cursor: pointer; outline: none;">
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇬🇧 EN</option>
                                <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>🇫🇷 FR</option>
                            </select>
                        </div>
                    </form>
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Sidebar Toggle Functionality
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const toggleIcon = document.getElementById('sidebarToggleIcon');
            
            sidebar.classList.toggle('collapsed');
            
            // Update icon
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-chevron-right');
                localStorage.setItem('teacherSidebarCollapsed', 'true');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-bars');
                localStorage.setItem('teacherSidebarCollapsed', 'false');
            }
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('teacherSidebarCollapsed') === 'true';
            const sidebar = document.querySelector('.sidebar');
            const toggleIcon = document.getElementById('sidebarToggleIcon');
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-chevron-right');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>


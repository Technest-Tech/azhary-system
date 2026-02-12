<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Azhary Academy Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #2d3748;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #e2e8f0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
            background: #1e293b;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #e2e8f0;
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
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
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
            color: #60a5fa;
        }

        .sidebar-logo-text {
            flex: 1;
            min-width: 0;
        }

        .sidebar-logo-text h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
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
            color: #64748b;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #cbd5e1;
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
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border-left: 3px solid #60a5fa;
        }

        .menu-item.active i {
            color: #60a5fa;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: #0f172a;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .user-info p {
            font-size: 12px;
            color: #64748b;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
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
            background: rgba(239, 68, 68, 0.2);
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        .top-navbar {
            background: #fff;
            padding: 16px 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
            color: #1e293b;
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
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .notification-icon:hover {
            background: #e2e8f0;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .content-area {
            padding: 32px;
            flex: 1;
            overflow-x: hidden;
            max-width: 100%;
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
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
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
                    <i class="fas fa-graduation-cap"></i>
                    <div class="sidebar-logo-text">
                        <h2>{{ __('admin.academy_name') }}</h2>
                        <p>{{ __('admin.admin_panel') }}</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">{{ __('admin.main_menu') }}</div>
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ __('admin.dashboard') }}</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">{{ __('admin.management') }}</div>
                    <a href="{{ route('admin.management') }}" class="menu-item {{ request()->routeIs('admin.management*') || request()->routeIs('admin.students.profile') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>{{ __('admin.management') }}</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="menu-item {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>{{ __('admin.analytics') }}</span>
                    </a>
                    <a href="{{ route('admin.teachers') }}" class="menu-item {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>{{ __('admin.teachers') }}</span>
                    </a>
                    <a href="{{ route('admin.students') }}" class="menu-item {{ request()->routeIs('admin.students*') && !request()->routeIs('admin.students.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>{{ __('admin.students') }}</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}" class="menu-item {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}" style="position: relative;">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('admin.notifications') }}</span>
                        @php
                            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span style="position: absolute; right: 20px; background: #ef4444; color: white; border-radius: 12px; padding: 2px 8px; font-size: 11px; font-weight: 700;">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.payment') }}" class="menu-item {{ request()->routeIs('admin.payment*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ __('admin.payment') }}</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">{{ __('admin.configuration') }}</div>
                    <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('admin.settings') }}</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ Auth::guard('admin')->user()->name }}</h4>
                        <p>{{ __('admin.administrator') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('admin.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-navbar">
                <h1 class="page-title">@yield('page-title')</h1>
                <div class="top-navbar-right">
                    <div class="language-switcher" style="margin-right: 16px;">
                        <form action="{{ route('language.switch') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="locale" value="{{ app()->getLocale() == 'en' ? 'fr' : 'en' }}">
                            <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 16px; color: #64748b;">
                                @if(app()->getLocale() == 'en')
                                    🇫🇷 FR
                                @else
                                    🇬🇧 EN
                                @endif
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('admin.notifications') }}" class="notification-icon" style="text-decoration: none; color: inherit;">
                        <i class="fas fa-bell"></i>
                        @php
                            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification-badge" style="display: block;"></span>
                            <span style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 10px; padding: 2px 6px; font-size: 10px; font-weight: 700; min-width: 18px; text-align: center;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </a>
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
                localStorage.setItem('adminSidebarCollapsed', 'true');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-bars');
                localStorage.setItem('adminSidebarCollapsed', 'false');
            }
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
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


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
            background: linear-gradient(180deg, #0f766e 0%, #064e3b 100%);
            color: #e2e8f0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #0f766e;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #14b8a6;
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo i {
            font-size: 32px;
            color: #5eead4;
        }

        .sidebar-logo-text h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: #99f6e4;
            font-weight: 500;
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
            color: #5eead4;
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
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(20, 184, 166, 0.2);
            color: #5eead4;
            border-left: 3px solid #5eead4;
        }

        .menu-item.active i {
            color: #5eead4;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
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
            background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
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
            color: #99f6e4;
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
            background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4);
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
        
        .language-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .language-switcher:hover {
            background: rgba(255,255,255,0.15);
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
                        <i class="fas fa-globe" style="color: #5eead4;"></i>
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
                        {{ __('teacher.logout') }}
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
                        <div class="language-switcher" style="background: #f1f5f9; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px;">
                            <i class="fas fa-globe" style="color: #64748b;"></i>
                            <select name="locale" onchange="this.form.submit()" style="background: transparent; border: none; color: #1e293b; font-size: 14px; font-weight: 600; cursor: pointer; outline: none;">
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

    @yield('scripts')
</body>
</html>


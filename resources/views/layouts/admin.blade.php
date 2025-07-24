<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Escolar') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --primary-color: #004AAD;
            --secondary-color: #C82222;
            --light-color: #FFF;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Admin Panel Layout */
        .admin-panel {
            display: flex;
            min-height: 100vh;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056d6 100%);
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-logo {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-img {
            width: 32px;
            height: 32px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .profile-section {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-avatar {
            position: relative;
        }

        .avatar-circle {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .status-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
        }

        .profile-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 20px;
            display: flex;
            justify-content: center;
        }

        .nav-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 0.5rem;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Panel */
        .main-panel {
            flex: 1;
            margin-left: 280px;
            background: var(--gray-50);
            min-height: 100vh;
        }

        /* Panel Header */
        .panel-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            background: var(--gray-50);
            font-size: 0.9rem;
            width: 300px;
        }

        .header-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--secondary-color);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .profile-btn:hover {
            background: var(--gray-100);
        }

        .profile-img {
            font-size: 1.5rem;
            color: var(--gray-600);
        }

        /* Panel Content */
        .panel-content {
            padding: 2rem;
        }

        /* Custom Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #003285;
            border-color: #003285;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .modern-sidebar.active {
                transform: translateX(0);
            }

            .main-panel {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .header-search {
                display: none;
            }

            .panel-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <!-- Modern Sidebar -->
        <div class="modern-sidebar">
            <!-- Logo Section -->
            <div class="sidebar-logo">
                <div class="logo-container">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
                    <span class="logo-text">Certificado Escolar</span>
                </div>
            </div>

            <!-- User Profile -->
            <div class="profile-section">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="status-indicator"></div>
                </div>
                <div class="profile-info">
                    <h6 class="profile-name">{{ Auth::user()->name }}</h6>
                    <span class="profile-role">Administrador</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <span class="nav-text">Escolas</span>
                    </a>

                    <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="nav-text">Alunos</span>
                    </a>

                    <a href="{{ route('notes.index') }}" class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="nav-text">Notas</span>
                    </a>

                    <a href="{{ route('historical-reports.index') }}" class="nav-link {{ request()->routeIs('historical-reports.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-scroll"></i>
                        </div>
                        <span class="nav-text">Histórico Escolar</span>
                    </a>

                    <a href="{{ route('certificates.index') }}" class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <span class="nav-text">Certificados</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Top Header -->
            <div class="panel-header">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        @yield('breadcrumb')
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-search">
                        <input type="text" placeholder="Buscar...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="header-notifications">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    <div class="header-profile">
                        <button class="profile-btn">
                            <div class="profile-img">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="panel-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.modern-sidebar');
            sidebar.classList.toggle('active');
        }

        // Auto-close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.modern-sidebar');
            const toggle = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

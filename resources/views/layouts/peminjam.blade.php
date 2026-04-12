<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - BookHub Sekolah</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles - PEMINJAM VERSION */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .sidebar-logo-text {
            color: white;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
        }

        .sidebar-section {
            padding: 8px 20px;
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 16px;
        }

        .sidebar-menu-item {
            margin: 4px 12px;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu-link:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu-link.active {
            background: rgba(255,255,255,0.25);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .sidebar-menu-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-menu-icon {
            margin-right: 12px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            color: white;
            backdrop-filter: blur(10px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: white;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            margin: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-nav-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #1e293b;
            cursor: pointer;
            padding: 8px;
        }

        .top-nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* Content Area */
        .content-area {
            background: #f8f9fa;
            min-height: calc(100vh - 73px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar PEMINJAM -->
    <aside class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="/peminjam/dashboard" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="bi bi-book"></i>
                </div>
                <span class="sidebar-logo-text">BookHub</span>
            </a>
        </div>

        <!-- Sidebar Menu PEMINJAM -->
        <ul class="sidebar-menu">
            <li class="sidebar-section">Menu Utama</li>
            
            <li class="sidebar-menu-item">
                <a href="/peminjam/dashboard" class="sidebar-menu-link {{ request()->is('peminjam/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 sidebar-menu-icon"></i>
                    Dashboard
                </a>
            </li>

            <li class="sidebar-section">Peminjaman</li>
            
            <li class="sidebar-menu-item">
                <a href="/peminjam/daftar-buku" class="sidebar-menu-link {{ request()->is('peminjam/daftar-buku') ? 'active' : '' }}">
                    <i class="bi bi-book sidebar-menu-icon"></i>
                    Daftar Buku
                </a>
            </li>
            
            <li class="sidebar-menu-item">
                <a href="/peminjam/ajukan-peminjaman" class="sidebar-menu-link {{ request()->is('peminjam/ajukan-peminjaman') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle sidebar-menu-icon"></i>
                    Ajukan Peminjaman
                </a>
            </li>
            
            <li class="sidebar-menu-item">
                <a href="/peminjam/kembalikan-buku" class="sidebar-menu-link {{ request()->is('peminjam/kembalikan-buku') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left sidebar-menu-icon"></i>
                    Kembalikan Buku
                </a>
            </li>

            <li class="sidebar-section">Riwayat</li>
            
            <li class="sidebar-menu-item">
                <a href="{{ route('peminjam.profile') }}" class="sidebar-menu-link {{ request()->is('peminjam/profile') ? 'active' : '' }}">
                    <i class="bi bi-clock-history sidebar-menu-icon"></i>
                    Riwayat Peminjaman
                </a>
            </li>

            <li class="sidebar-section">Akun</li>
            
            <li class="sidebar-menu-item">
                <a href="{{ route('peminjam.profile.siswa') }}" class="sidebar-menu-link {{ request()->is('peminjam/profile-siswa') ? 'active' : '' }}">
                    <i class="bi bi-person-circle sidebar-menu-icon"></i>
                    Profil Saya
                </a>
            </li>
        </ul>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-details">
                    <p class="user-name">{{ Auth::user()->name }}</p>
                    <p class="user-role">{{ Auth::user()->kelas ?? 'Peminjam' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="top-nav-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark">{{ Auth::user()->name }} ({{ Auth::user()->kelas ?? 'Peminjam' }})</h5>
            </div>
            <div class="top-nav-right">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if(menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
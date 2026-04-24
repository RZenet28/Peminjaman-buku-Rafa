<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - BookHub Sekolah</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">

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

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
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
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
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
            color: #94a3b8;
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
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu-link.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
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
            color: #94a3b8;
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .overlay.active {
                display: block;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-area>* {
            animation: slideIn 0.4s ease-out;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="bi bi-book"></i>
                </div>
                <span class="sidebar-logo-text">BookHub</span>
            </a>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="sidebar-section">Sistem</li>

            <li class="sidebar-menu-item">
                <a href="/admin" class="sidebar-menu-link active">
                    <i class="bi bi-speedometer2 sidebar-menu-icon"></i>
                    Dashboard
                </a>
            </li>

            <li class="sidebar-section">Manajemen</li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.books.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-book sidebar-menu-icon"></i>
                    Data Buku
                </a>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.categories.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-tags sidebar-menu-icon"></i>
                    Kategori Buku
                </a>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.users.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-people sidebar-menu-icon"></i>
                    User
                </a>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.borrowing.approval') }}" class="sidebar-menu-link">
                    <i class="bi bi-check-circle sidebar-menu-icon"></i>
                    Menyetujui Peminjaman
                </a>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.borrowing.return_approval') }}" class="sidebar-menu-link">
                    <i class="bi bi-check2-all sidebar-menu-icon"></i>
                    Menyetujui Pengembalian
                </a>
            </li>

            {{-- <li class="sidebar-menu-item">
                <a href="{{ route('admin.borrowing.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-journal-text sidebar-menu-icon"></i>
                    Kelola Peminjaman
                </a>
            </li> --}}

            <li class="sidebar-section">Laporan</li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.reporting.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-bar-chart sidebar-menu-icon"></i>
                    Laporan Peminjaman
                </a>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.history.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-clock-history sidebar-menu-icon"></i>
                    Riwayat Peminjaman
                </a>
            </li>

            {{-- <li class="sidebar-section">Pengaturan</li>

            <li class="sidebar-menu-item">
                <a href="{{ route('admin.settings.index') }}" class="sidebar-menu-link">
                    <i class="bi bi-gear sidebar-menu-icon"></i>
                    Pengaturan Sistem
                </a>
            </li> --}}
        </ul>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    A
                </div>
                <div class="user-details">
                    <p class="user-name">Admin</p>
                    <p class="user-role">Administrator</p>
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
                <h5 class="mb-0 fw-bold text-dark">admin (admin)</h5>
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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Show SweetAlert notification if session has message
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @elseif(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK'
            });
        @elseif(session('warning'))
            Swal.fire({
                title: 'Peringatan!',
                text: '{{ session('warning') }}',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
        @elseif(session('info'))
            Swal.fire({
                title: 'Informasi',
                text: '{{ session('info') }}',
                icon: 'info',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
        @endif

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Active Menu Item
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.sidebar-menu-link');

        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // ========== SweetAlert2 Confirm Dialog Helpers ==========

        // Function untuk confirm dialog dengan form submit
        function confirmSubmit(message, form) {
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        // Function untuk simple alert
        function showAlert(title, message, icon = 'info') {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'OK'
            });
        }

        // Attach confirm to all forms with data-confirm attribute
        document.querySelectorAll('form[data-confirm]').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const message = this.getAttribute('data-confirm');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Attach confirm to all buttons with data-confirm attribute
        document.querySelectorAll('button[data-confirm], a[data-confirm]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const message = this.getAttribute('data-confirm');
                const form = this.closest('form');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (form) {
                            form.submit();
                        } else if (this.href) {
                            window.location.href = this.href;
                        }
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Surat Menyurat')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 300px;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar-header {
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sidebar-filter-form {
            margin: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            width: 100%;
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.9);
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .sidebar-menu-link:hover,
        .sidebar-menu-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: rgba(255,255,255,0.5);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
            padding-left: 18px;
        }
        .sidebar-menu a.active::before,
        .sidebar-menu a:hover::before {
            transform: scaleY(1);
        }
        .sidebar-menu a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .sidebar-menu-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .sidebar-submenu {
            list-style: none;
            padding: 0;
            margin: 0 0 0 15px;
            margin-top: 5px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-submenu li {
            margin-bottom: 0;
        }
        .sidebar-submenu.show li:nth-child(1) {
            transition-delay: 0.1s;
        }
        .sidebar-submenu.show li:nth-child(2) {
            transition-delay: 0.15s;
        }
        .sidebar-submenu.show li:nth-child(3) {
            transition-delay: 0.2s;
        }
        .sidebar-submenu.show li:nth-child(4) {
            transition-delay: 0.25s;
        }
        .sidebar-submenu a {
            padding: 6px 10px 6px 25px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        .sidebar-submenu a:hover {
            background: rgba(255,255,255,0.12);
        }
        .user-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .user-info .badge {
            font-size: 0.75rem;
        }
        .top-navbar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-left: 300px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
            transform: scale(1.01);
        }
        .alert {
            animation: slideDown 0.4s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .top-navbar {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Top Navbar -->
    <div class="top-navbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-envelope-paper"></i> Sistem Surat Menyurat</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" 
                         alt="Foto Profil" 
                         class="rounded-circle"
                         style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #dee2e6;">
                @else
                    <i class="bi bi-person-circle text-muted" style="font-size: 1.5rem;"></i>
                @endif
                <span class="text-muted">{{ auth()->user()->name }}</span>
                <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-grid-3x3-gap"></i> Menu</h4>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->role === 'user')
            <li>
                <a href="{{ route('surat_masuk.index') }}" class="{{ request()->routeIs('surat_masuk.index') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Surat Saya</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat_masuk.create') }}" class="{{ request()->routeIs('surat_masuk.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Buat Surat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('divisions.index') }}" class="{{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Divisi</span>
                </a>
            </li>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'petugas']))
            <li>
                <form action="{{ auth()->user()->role === 'admin' ? route('admin.surat_masuk.tab') : route('petugas.surat_masuk.tab') }}" method="POST" class="sidebar-filter-form">
                    @csrf
                    <input type="hidden" name="tab" value="masuk">
                    <button type="submit" class="sidebar-menu-link {{ request()->routeIs('surat_masuk.index') && (session('admin_letters_tab', session('petugas_letters_tab', 'masuk')) === 'masuk') ? 'active' : '' }}">
                        <i class="bi bi-inbox"></i>
                        <span>Surat Masuk</span>
                    </button>
                </form>
            </li>
            <li>
                <form action="{{ auth()->user()->role === 'admin' ? route('admin.surat_masuk.tab') : route('petugas.surat_masuk.tab') }}" method="POST" class="sidebar-filter-form">
                    @csrf
                    <input type="hidden" name="tab" value="keluar">
                    <button type="submit" class="sidebar-menu-link {{ request()->routeIs('surat_masuk.index') && (session('admin_letters_tab', session('petugas_letters_tab', 'masuk')) === 'keluar') ? 'active' : '' }}">
                        <i class="bi bi-send"></i>
                        <span>Surat Keluar</span>
                    </button>
                </form>
            </li>
            @endif

            @if(auth()->user()->role === 'petugas')
            <li>
                <a href="{{ route('petugas.users.pending') }}" class="{{ request()->routeIs('petugas.users.*') ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split"></i>
                    <span>Approval User</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Manajemen User</span>
                </a>
                @if(request()->routeIs('users.*'))
                <ul class="sidebar-submenu show">
                    <li>
                        <a href="{{ route('users.pending') }}" 
                           class="{{ request()->routeIs('users.pending') ? 'active' : '' }}">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Pending Approval</span>
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('users.filter.apply') }}" method="POST" class="sidebar-filter-form">
                            @csrf
                            <input type="hidden" name="role_filter" value="admin">
                            <input type="hidden" name="search" value="{{ session('admin_users_filters.search') }}">
                            <button type="submit" class="sidebar-menu-link {{ (session('admin_users_filters.role_filter') ?? '') === 'admin' ? 'active' : '' }}">
                                <i class="bi bi-shield-check"></i>
                                <span>Admin</span>
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('users.filter.apply') }}" method="POST" class="sidebar-filter-form">
                            @csrf
                            <input type="hidden" name="role_filter" value="petugas">
                            <input type="hidden" name="search" value="{{ session('admin_users_filters.search') }}">
                            <button type="submit" class="sidebar-menu-link {{ (session('admin_users_filters.role_filter') ?? '') === 'petugas' ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i>
                                <span>Petugas</span>
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('users.filter.apply') }}" method="POST" class="sidebar-filter-form">
                            @csrf
                            <input type="hidden" name="role_filter" value="user">
                            <input type="hidden" name="search" value="{{ session('admin_users_filters.search') }}">
                            <button type="submit" class="sidebar-menu-link {{ (session('admin_users_filters.role_filter') ?? '') === 'user' ? 'active' : '' }}">
                                <i class="bi bi-person"></i>
                                <span>User</span>
                            </button>
                        </form>
                    </li>
                </ul>
                @endif
            </li>
            <li>
                <a href="{{ route('divisions.index') }}" class="{{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Manajemen Divisi</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin')
            <li style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                <a href="{{ route('activity_logs.index') }}" class="{{ request()->routeIs('activity_logs.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Profil Saya</span>
                </a>
            </li>
        </ul>

        <div class="user-info">
            <div class="d-flex align-items-center mb-2">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" 
                         alt="Foto Profil" 
                         class="rounded-circle me-2"
                         style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                @else
                    <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                @endif
                <div>
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-white-50">{{ auth()->user()->email }}</small>
                </div>
            </div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">
                    <i class="bi bi-building"></i> {{ auth()->user()->division->name ?? 'Tidak ada divisi' }}
                </span>
            </div>
        </div>
    </div>
    @endauth

    <!-- Main Content -->
    <div class="main-content fade-in">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>


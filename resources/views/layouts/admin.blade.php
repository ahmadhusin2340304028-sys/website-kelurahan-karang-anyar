<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --gov-blue: #1a4f8a;
            --gov-blue-dark: #123a6a;
            --sidebar-width: 260px;
        }

        body { background: #f0f2f5; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--gov-blue-dark);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.3s;
            overflow-y: auto;
        }
        #sidebar .sidebar-brand {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.6rem 1.5rem;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 0;
            transition: background 0.2s;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.12);
        }
        #sidebar .nav-link i { font-size: 1rem; width: 20px; }
        #sidebar .sidebar-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            padding: 1rem 1.5rem 0.3rem;
        }

        /* Main content */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar admin */
        #admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Stats cards */
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .stat-card .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* Tables */
        .table-admin th { background: #f8f9fa; font-weight: 600; font-size: 0.85rem; }

        /* Badge count */
        .sidebar-badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 2px 7px;
        }

        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-building me-2"></i>
        Admin Panel
        <div class="fw-normal text-white-50 small mt-1">Kelurahan Karang Anyar</div>
    </div>

    <nav class="py-2">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section">Konten</div>
        <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i> Berita
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Kategori
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i> Pengumuman
        </a>
        <a href="{{ route('admin.galleries.index') }}" class="nav-link {{ request()->routeIs('admin.galleries*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Galeri
        </a>

        <div class="sidebar-section">Profil & SDM</div>
        <a href="{{ route('admin.officials.index') }}" class="nav-link {{ request()->routeIs('admin.officials*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Data ASN
        </a>
        <a href="{{ route('admin.organization.index') }}" class="nav-link {{ request()->routeIs('admin.organization*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i> Struktur Org.
        </a>

        <div class="sidebar-section">UMKM</div>
        <a href="{{ route('admin.umkms.index') }}" class="nav-link {{ request()->routeIs('admin.umkms*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> Data UMKM
            @php $pendingCount = \App\Models\Umkm::pending()->count(); @endphp
            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Pengaturan Website
        </a>
    </nav>
</div>

{{-- Main --}}
<div id="main-content">
    {{-- Topbar --}}
    <div id="admin-topbar" class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <span class="text-muted small">
                    <i class="bi bi-house-door me-1"></i>
                    @yield('breadcrumb', 'Dashboard')
                </span>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Website
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    {{-- Flash --}}
    <div class="container-fluid px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Ada kesalahan pada input:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <div class="container-fluid px-4 pb-5 flex-grow-1">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>

@stack('scripts')
</body>
</html>

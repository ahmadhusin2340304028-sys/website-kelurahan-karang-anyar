<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>@yield('title', config('app.name', 'Kelurahan Karang Anyar'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Website resmi Kelurahan Karang Anyar'))">
    <meta name="keywords" content="@yield('meta_keywords', 'kelurahan, karang anyar, pemerintahan')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', \App\Models\Setting::get('site_description'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">

    {{-- Favicon --}}
    <link rel="icon" href="{{ \App\Models\Setting::get('favicon') ? \Illuminate\Support\Facades\Storage::url(\App\Models\Setting::get('favicon')) : asset('images/favicon.png') }}" type="image/png">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --gov-blue: #1a4f8a;
            --gov-blue-dark: #123a6a;
            --gov-blue-light: #e8f0fb;
            --gov-gold: #c8a951;
            --text-dark: #212529;
            --text-muted: #6c757d;
        }

        body { font-family: 'Segoe UI', Arial, sans-serif; color: var(--text-dark); }

        /* Top bar */
        .topbar { background: var(--gov-blue-dark); font-size: 0.82rem; padding: 5px 0; }
        .topbar a { color: rgba(255,255,255,0.85); text-decoration: none; }
        .topbar a:hover { color: #fff; }

        /* Navbar */
        .navbar-gov { background: var(--gov-blue); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .navbar-gov .navbar-brand { font-weight: 700; font-size: 1.1rem; color: #fff !important; }
        .navbar-gov .nav-link { color: rgba(255,255,255,0.9) !important; font-size: 0.93rem; font-weight: 500; padding: 0.5rem 0.85rem !important; }
        .navbar-gov .nav-link:hover, .navbar-gov .nav-link.active { color: #fff !important; background: rgba(255,255,255,0.15); border-radius: 4px; }
        .navbar-gov .dropdown-menu { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.12); }

        /* Hero */
        .hero-section { background: linear-gradient(135deg, var(--gov-blue) 0%, var(--gov-blue-dark) 100%); color: #fff; padding: 80px 0; }

        /* Section titles */
        .section-title { font-weight: 700; color: var(--gov-blue); position: relative; margin-bottom: 0.5rem; }
        .section-title::after { content: ''; display: block; width: 50px; height: 3px; background: var(--gov-gold); margin-top: 8px; }
        .section-title.center::after { margin: 8px auto 0; }

        /* Cards */
        .card-news { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
        .card-news:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
        .card-news .card-img-top { height: 200px; object-fit: cover; }
        .badge-category { background: var(--gov-blue); font-size: 0.75rem; }

        /* Announcements */
        .announcement-bar { background: #fff3cd; border-left: 4px solid var(--gov-gold); }

        /* Officials */
        .official-card img { width: 90px; height: 90px; object-fit: cover; border-radius: 50%; border: 3px solid var(--gov-blue); }

        /* Footer */
        .footer-gov { background: var(--gov-blue-dark); color: rgba(255,255,255,0.85); }
        .footer-gov h6 { color: #fff; font-weight: 600; }
        .footer-gov a { color: rgba(255,255,255,0.75); text-decoration: none; }
        .footer-gov a:hover { color: #fff; }
        .footer-bottom { background: rgba(0,0,0,0.2); font-size: 0.82rem; }

        /* Gallery */
        .gallery-item { overflow: hidden; border-radius: 8px; cursor: pointer; }
        .gallery-item img { height: 200px; object-fit: cover; width: 100%; transition: transform 0.3s; }
        .gallery-item:hover img { transform: scale(1.05); }

        /* UMKM card */
        .umkm-card img { height: 220px; object-fit: cover; }

        /* Breadcrumb */
        .breadcrumb-gov { background: var(--gov-blue-light); padding: 12px 0; }
        .breadcrumb-gov .breadcrumb-item a { color: var(--gov-blue); }

        /* Lazy load */
        img[loading="lazy"] { opacity: 0; transition: opacity 0.3s; }
        img[loading="lazy"].loaded { opacity: 1; }

        /* Search bar */
        .search-form .form-control { border-right: 0; }
        .search-form .btn { border-left: 0; background: var(--gov-blue); color: #fff; }

        /* Pagination */
        .page-item.active .page-link { background: var(--gov-blue); border-color: var(--gov-blue); }
        .page-link { color: var(--gov-blue); }

        @media (max-width: 768px) {
            .hero-section { padding: 50px 0; }
            .hero-section h1 { font-size: 1.6rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Top Bar --}}
    <div class="topbar d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-geo-alt-fill me-1"></i>
                {{ \App\Models\Setting::get('address', 'Jl. Karang Anyar No. 1') }}
            </div>
            <div class="d-flex gap-3">
                @if(\App\Models\Setting::get('phone'))
                    <span><i class="bi bi-telephone-fill me-1"></i>{{ \App\Models\Setting::get('phone') }}</span>
                @endif
                @if(\App\Models\Setting::get('email'))
                    <a href="mailto:{{ \App\Models\Setting::get('email') }}">
                        <i class="bi bi-envelope-fill me-1"></i>{{ \App\Models\Setting::get('email') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <nav class="navbar navbar-gov navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                @if(\App\Models\Setting::get('logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Setting::get('logo')) }}"
                         alt="Logo" height="40">
                @else
                    <i class="bi bi-building fs-4"></i>
                @endif
                <span>{{ \App\Models\Setting::get('kelurahan_name', 'Kelurahan Karang Anyar') }}</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('profil*') ? 'active' : '' }}"
                           href="#" data-bs-toggle="dropdown">Profil</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Profil Kelurahan</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.history') }}">Sejarah</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.vision') }}">Visi & Misi</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.officials') }}">Pejabat</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.organization') }}">Struktur Organisasi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}"
                           href="{{ route('news.index') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}"
                           href="{{ route('gallery.index') }}">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('umkm.*') ? 'active' : '' }}"
                           href="{{ route('umkm.index') }}">UMKM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.contact') ? 'active' : '' }}"
                           href="{{ route('profile.contact') }}">Kontak</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <form action="{{ route('search') }}" method="GET" class="d-flex search-form">
                            <input type="search" name="q" class="form-control form-control-sm"
                                   placeholder="Cari..." value="{{ request('q') }}" style="width: 150px;">
                            <button type="submit" class="btn btn-sm">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Active Announcements Banner --}}
    @php $activeAnnouncements = \App\Models\Announcement::active()->take(3)->get(); @endphp
    @if($activeAnnouncements->isNotEmpty())
        <div class="announcement-bar py-2">
            <div class="container">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark me-2">
                        <i class="bi bi-megaphone-fill"></i> Pengumuman
                    </span>
                    <div id="announcementTicker">
                        @foreach($activeAnnouncements as $ann)
                            <span class="{{ !$loop->first ? 'd-none' : '' }}">{{ $ann->title }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    <div class="container mt-2">
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
    </div>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="footer-gov pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h6 class="mb-3">
                        <i class="bi bi-building me-2"></i>
                        {{ \App\Models\Setting::get('kelurahan_name', 'Kelurahan Karang Anyar') }}
                    </h6>
                    <p class="small">{{ \App\Models\Setting::get('site_description', 'Website resmi Kelurahan Karang Anyar. Melayani masyarakat dengan sepenuh hati.') }}</p>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <h6 class="mb-3">Menu</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-1"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="mb-1"><a href="{{ route('news.index') }}">Berita</a></li>
                        <li class="mb-1"><a href="{{ route('gallery.index') }}">Galeri</a></li>
                        <li class="mb-1"><a href="{{ route('umkm.index') }}">UMKM</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <h6 class="mb-3">Profil</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-1"><a href="{{ route('profile.history') }}">Sejarah</a></li>
                        <li class="mb-1"><a href="{{ route('profile.vision') }}">Visi & Misi</a></li>
                        <li class="mb-1"><a href="{{ route('profile.officials') }}">Pejabat</a></li>
                        <li class="mb-1"><a href="{{ route('profile.organization') }}">Struktur Org.</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="mb-3">Kontak</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            {{ \App\Models\Setting::get('address', '-') }}
                        </li>
                        @if(\App\Models\Setting::get('phone'))
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            {{ \App\Models\Setting::get('phone') }}
                        </li>
                        @endif
                        @if(\App\Models\Setting::get('email'))
                        <li class="mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>
                            {{ \App\Models\Setting::get('email') }}
                        </li>
                        @endif
                        @if(\App\Models\Setting::get('office_hours'))
                        <li>
                            <i class="bi bi-clock-fill me-2"></i>
                            {{ \App\Models\Setting::get('office_hours') }}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-4 pt-3 text-center">
            <div class="container">
                <span>© {{ date('Y') }} {{ \App\Models\Setting::get('kelurahan_name', 'Kelurahan Karang Anyar') }}. Hak cipta dilindungi.</span>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Lazy load images
        document.addEventListener('DOMContentLoaded', () => {
            const imgs = document.querySelectorAll('img[loading="lazy"]');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('loaded'); });
            });
            imgs.forEach(img => observer.observe(img));

            // Announcement ticker
            const items = document.querySelectorAll('#announcementTicker span');
            if (items.length > 1) {
                let idx = 0;
                setInterval(() => {
                    items[idx].classList.add('d-none');
                    idx = (idx + 1) % items.length;
                    items[idx].classList.remove('d-none');
                }, 4000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

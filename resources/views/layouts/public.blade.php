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

        /* ── Announcement bar ──────────────────────────────────── */
        .announcement-bar {
            background: linear-gradient(90deg, #fffbea 0%, #fff9e0 100%);
            border-left: 4px solid var(--gov-gold);
            border-bottom: 1px solid #f0d87a;
        }
        .announcement-ticker-wrap {
            overflow: hidden;
            flex: 1;
            min-width: 0;
        }
        .announcement-ticker-wrap span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
        .ann-detail-btn {
            font-size: 0.78rem;
            padding: 2px 10px;
            border-color: var(--gov-gold);
            color: #856404;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .ann-detail-btn:hover {
            background: var(--gov-gold);
            color: #fff;
            border-color: var(--gov-gold);
        }
        /* Modal pengumuman */
        #announcementModal .modal-header {
            background: linear-gradient(135deg, var(--gov-blue) 0%, var(--gov-blue-dark) 100%);
            color: #fff;
        }
        #announcementModal .modal-header .btn-close { filter: invert(1); }
        #announcementModal .ann-meta {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.83rem;
        }
        #announcementModal .ann-content-body {
            font-size: 0.97rem;
            line-height: 1.75;
            color: #2d2d2d;
            white-space: pre-wrap;
        }
        /* Nav dots for multi-announcement */
        .ann-nav-dots { display: flex; gap: 6px; align-items: center; }
        .ann-nav-dots .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #ccc; cursor: pointer; transition: background .2s;
        }
        .ann-nav-dots .dot.active { background: var(--gov-gold); }

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
                            <li><a class="dropdown-item" href="{{ route('profile.officials') }}">Profil ASN</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.organization') }}">Struktur Organisasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.peta') }}">Peta Wilayah</a></li>
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

    {{-- ══ Active Announcements Banner ══════════════════════════════════════ --}}
    @php
        $activeAnnouncements = \App\Models\Announcement::active()
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();
    @endphp
    @if($activeAnnouncements->isNotEmpty())
    <div class="announcement-bar py-2">
        <div class="container">
            <div class="d-flex align-items-center gap-2">

                {{-- Label --}}
                <span class="badge bg-warning text-dark flex-shrink-0">
                    <i class="bi bi-megaphone-fill me-1"></i>Pengumuman
                </span>

                {{-- Ticker teks --}}
                <div class="announcement-ticker-wrap">
                    @foreach($activeAnnouncements as $ann)
                        <span id="annTick-{{ $ann->id }}"
                              class="{{ !$loop->first ? 'd-none' : '' }} fw-semibold text-dark small">
                            {{ $ann->title }}
                        </span>
                    @endforeach
                </div>

                {{-- Tombol Selengkapnya --}}
                <button type="button"
                        class="btn btn-sm btn-outline-warning ann-detail-btn"
                        id="annDetailBtn"
                        data-bs-toggle="modal"
                        data-bs-target="#announcementModal"
                        data-index="0">
                    <i class="bi bi-info-circle me-1"></i>Selengkapnya
                </button>

            </div>

            {{-- Nav dots (jika >1 pengumuman) --}}
            @if($activeAnnouncements->count() > 1)
            <div class="d-flex justify-content-center mt-1">
                <div class="ann-nav-dots">
                    @foreach($activeAnnouncements as $i => $ann)
                        <span class="dot {{ $i === 0 ? 'active' : '' }}"
                              data-index="{{ $i }}"
                              title="{{ $ann->title }}"></span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══ Modal Detail Pengumuman ══════════════════════════════════════════ --}}
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone-fill fs-5"></i>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="annModalTitle">Pengumuman</h5>
                            <small class="text-white-50" id="annModalCount"></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Meta info --}}
                    <div class="ann-meta mb-3 d-flex flex-wrap gap-3">
                        <span id="annModalDate">
                            <i class="bi bi-calendar3 me-1 text-primary"></i>
                            <span></span>
                        </span>
                        <span id="annModalUntil" class="d-none">
                            <i class="bi bi-calendar-x me-1 text-danger"></i>
                            Berlaku hingga: <span></span>
                        </span>
                    </div>

                    {{-- Isi pengumuman --}}
                    <div class="ann-content-body" id="annModalContent"></div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{-- Navigasi antar pengumuman --}}
                    <div class="d-flex align-items-center gap-2">
                        @if($activeAnnouncements->count() > 1)
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="annPrevBtn">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <span class="small text-muted" id="annPageInfo"></span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="annNextBtn">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Data pengumuman untuk JS --}}
    @php
        $annData = $activeAnnouncements->map(function ($a) {
            return [
                'id'         => $a->id,
                'title'      => $a->title,
                'content'    => $a->content,
                'start_date' => $a->start_date->translatedFormat('d F Y'),
                'end_date'   => $a->end_date ? $a->end_date->translatedFormat('d F Y') : null,
            ];
        })->values()->toArray();
    @endphp
    <script>
        window._announcements = @json($annData);
    </script>
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
                        <li class="mb-1"><a href="{{ route('profile.officials') }}">Profil ASN</a></li>
                        <li class="mb-1"><a href="{{ route('profile.organization') }}">Struktur Org.</a></li>
                        <li class="mb-1"><a href="{{ route('profile.peta') }}">Peta Wilayah</a></li>
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
    document.addEventListener('DOMContentLoaded', () => {

        // ── Lazy load images ───────────────────────────────────────────
        const imgs = document.querySelectorAll('img[loading="lazy"]');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('loaded'); });
        });
        imgs.forEach(img => observer.observe(img));

        // ── Announcement ticker & modal ────────────────────────────────
        const anns = window._announcements || [];
        if (!anns.length) return;

        let currentIdx = 0;
        const tickInterval = 5000; // ms antar pergantian
        let tickTimer = null;

        // Elemen ticker
        const tickSpans   = document.querySelectorAll('[id^="annTick-"]');
        const dots        = document.querySelectorAll('.ann-nav-dots .dot');
        const detailBtn   = document.getElementById('annDetailBtn');

        // Elemen modal
        const modalTitle   = document.getElementById('annModalTitle');
        const modalCount   = document.getElementById('annModalCount');
        const modalDate    = document.querySelector('#annModalDate span');
        const modalUntil   = document.getElementById('annModalUntil');
        const modalUntilTx = document.querySelector('#annModalUntil span');
        const modalContent = document.getElementById('annModalContent');
        const pageInfo     = document.getElementById('annPageInfo');
        const prevBtn      = document.getElementById('annPrevBtn');
        const nextBtn      = document.getElementById('annNextBtn');

        // Ganti tampilan ticker ke index tertentu
        function showTicker(idx) {
            tickSpans.forEach(s => s.classList.add('d-none'));
            dots.forEach(d => d.classList.remove('active'));
            const target = document.getElementById('annTick-' + anns[idx].id);
            if (target) target.classList.remove('d-none');
            if (dots[idx]) dots[idx].classList.add('active');
            currentIdx = idx;
        }

        // Isi konten modal
        function fillModal(idx) {
            const a = anns[idx];
            if (!a) return;
            modalTitle.textContent   = a.title;
            modalCount.textContent   = anns.length > 1 ? `${idx + 1} dari ${anns.length} pengumuman` : '';
            modalDate.textContent    = 'Mulai berlaku: ' + a.start_date;
            if (a.end_date) {
                modalUntil.classList.remove('d-none');
                modalUntilTx.textContent = a.end_date;
            } else {
                modalUntil.classList.add('d-none');
            }
            // Tampilkan konten dengan newline dihormati
            modalContent.textContent = a.content;
            if (pageInfo) pageInfo.textContent = `${idx + 1} / ${anns.length}`;
        }

        // Auto-ticker
        function startTicker() {
            if (anns.length <= 1) return;
            tickTimer = setInterval(() => {
                const next = (currentIdx + 1) % anns.length;
                showTicker(next);
            }, tickInterval);
        }
        function resetTicker() {
            clearInterval(tickTimer);
            startTicker();
        }

        // Klik tombol "Selengkapnya"
        if (detailBtn) {
            detailBtn.addEventListener('click', () => {
                fillModal(currentIdx);
            });
        }

        // Klik dot navigator
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const idx = parseInt(dot.dataset.index);
                showTicker(idx);
                resetTicker();
            });
        });

        // Navigasi prev/next di modal
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                const idx = (currentIdx - 1 + anns.length) % anns.length;
                showTicker(idx);
                resetTicker();
                fillModal(idx);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const idx = (currentIdx + 1) % anns.length;
                showTicker(idx);
                resetTicker();
                fillModal(idx);
            });
        }

        // Update modal saat dibuka (ikut currentIdx yang sedang tampil di ticker)
        const modalEl = document.getElementById('announcementModal');
        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', () => fillModal(currentIdx));
        }

        // Mulai ticker
        startTicker();
    });
    </script>

    @stack('scripts')
</body>
</html>
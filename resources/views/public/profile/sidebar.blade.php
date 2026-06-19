<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush rounded">
            <a href="{{ route('profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>Profil Kelurahan
            </a>
            <a href="{{ route('profile.history') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.history') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-2"></i>Sejarah
            </a>
            <a href="{{ route('profile.vision') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.vision') ? 'active' : '' }}">
                <i class="bi bi-eye me-2"></i>Visi & Misi
            </a>
            <a href="{{ route('profile.officials') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.officials') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>Pejabat
            </a>
            <a href="{{ route('profile.peta') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.peta') ? 'active' : '' }}">
                <i class="bi bi-map me-2"></i>Peta Wilayah
            </a>
            <a href="{{ route('profile.organization') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.organization') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 me-2"></i>Struktur Organisasi
            </a>
            <a href="{{ route('profile.contact') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.contact') ? 'active' : '' }}">
                <i class="bi bi-telephone me-2"></i>Kontak
            </a>
        </div>
    </div>
</div>

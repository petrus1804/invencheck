<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/IC.png') }}" alt="InvenCheck" class="brand-mark">
        <div>
            <div class="brand-name">InvenCheck</div>
            <div class="brand-sub">Warehouse System</div>
        </div>
    </div>

    <nav class="sidebar-nav">

        @php $u = auth()->user(); @endphp

        <span class="nav-label">Menu</span>

        @if($u->hasPermission('view_dashboard'))
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                        <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                        <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                    </svg>
                </span>
                Dashboard
            </a>
        @endif

        @if($u->hasPermission('view_stock'))
            <a href="{{ route('stock') }}" class="nav-item {{ request()->routeIs('stock') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8L12 3 3 8v8l9 5 9-5V8z"></path>
                        <path d="M3 8l9 5 9-5"></path>
                        <path d="M12 13v8"></path>
                    </svg>
                </span>
                Stok Barang
            </a>
        @endif

        @if($u->hasPermission('view_categories'))
            <a href="{{ route('kategori') }}" class="nav-item {{ request()->routeIs('kategori') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41L11 3.83A2 2 0 009.59 3H4a1 1 0 00-1 1v5.59a2 2 0 00.59 1.41l9.58 9.58a2 2 0 002.82 0l4.6-4.6a2 2 0 000-2.82z"></path>
                        <circle cx="7.5" cy="7.5" r="1.5"></circle>
                    </svg>
                </span>
                Kategori
            </a>
        @endif

        @if($u->hasPermission('view_warehouses'))
            <a href="{{ route('gudang') }}" class="nav-item {{ request()->routeIs('gudang') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21V10l9-6 9 6v11"></path>
                        <path d="M3 21h18"></path>
                        <path d="M9 21v-6h6v6"></path>
                    </svg>
                </span>
                Gudang
            </a>
        @endif

        @if($u->hasPermission('view_reports'))
            <a href="{{ route('laporan') }}" class="nav-item {{ request()->routeIs('laporan') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"></path>
                        <rect x="7" y="12" width="3" height="6"></rect>
                        <rect x="13" y="8" width="3" height="10"></rect>
                    </svg>
                </span>
                Laporan
            </a>
        @endif

        @if($u->hasPermission('approve_requests'))
            <a href="{{ route('persetujuan') }}" class="nav-item {{ request()->routeIs('persetujuan') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </span>
                Persetujuan
                @if(($pendingCount ?? 0) > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        @endif

        @if($u->hasPermission('request_items'))
            <a href="{{ route('ambil-barang') }}" class="nav-item {{ request()->routeIs('ambil-barang') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8L12 3 3 8v8l9 5 9-5V8z"></path>
                        <path d="M3 8l9 5 9-5"></path>
                        <path d="M12 13v8"></path>
                    </svg>
                </span>
                Ambil Barang
            </a>

            <a href="{{ route('riwayat-saya') }}" class="nav-item {{ request()->routeIs('riwayat-saya') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </span>
                Riwayat Saya
            </a>
        @endif

        @if($u->hasPermission('manage_users'))
            <span class="nav-label">Lainnya</span>

            <a href="{{ route('pengaturan') }}" class="nav-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"></path>
                    </svg>
                </span>
                Pengaturan
            </a>

            <a href="{{ route('roles') }}" class="nav-item {{ request()->routeIs('roles') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 010 7.75"></path>
                    </svg>
                </span>
                Role
            </a>
        @endif

    </nav>
</aside>
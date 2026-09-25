<header class="topbar">
    <div class="search-box">
        <span class="search-icon">⌕</span>
        <input type="text" placeholder="Cari kode barang atau nama...">
    </div>

    <div class="topbar-actions">
        <button class="icon-btn" title="Notifikasi">
            🔔
            <span class="badge-dot"></span>
        </button>

        <div class="user-menu">
            <button class="user-chip" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </button>

            <div class="user-dropdown" id="userDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    👤 Edit Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
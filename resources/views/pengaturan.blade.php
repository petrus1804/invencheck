@extends('layouts.app')

@section('title', 'Pengaturan - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('pengaturan.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Pengaturan</h1>
            <p>Kelola profil, pengguna, dan konfigurasi sistem InvenCheck</p>
        </div>
    </div>

    <div class="settings-layout">

        {{-- TAB NAV --}}
        <div class="settings-nav">
            <button class="settings-tab active" data-tab="profil">👤 Profil & Akun</button>
            <button class="settings-tab" data-tab="stok">📦 Pengaturan Stok</button>
            <button class="settings-tab" data-tab="pengguna">👥 Manajemen Pengguna</button>
            <button class="settings-tab" data-tab="notifikasi">🔔 Notifikasi</button>
            <button class="settings-tab" data-tab="data-master">🗂 Data Master</button>
            <button class="settings-tab" data-tab="sistem">⚙ Sistem & Umum</button>
            <button class="settings-tab" data-tab="keamanan">🔒 Keamanan</button>
        </div>

        {{-- TAB CONTENT --}}
        <div class="settings-content">

            {{-- PROFIL & AKUN --}}
            <div class="settings-panel active" id="tab-profil">
        <div class="panel">
            <div class="panel-head"><h2>Profil</h2></div>

            @if(session('status') === 'profile-updated')
                <div class="alert-success">Profil berhasil diperbarui.</div>
            @endif

            <div class="profile-row">
                <div class="avatar large">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div>
                    <p class="hint-text">Foto profil pakai inisial otomatis dari namamu.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="{{ ucfirst(auth()->user()->role) }}" disabled>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Ganti Password</h2></div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••" required>
                        @error('current_password', 'updatePassword')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                        @error('password', 'updatePassword')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Update Password</button>
            </form>
        </div>
    </div>

            {{-- PENGATURAN STOK --}}
            <div class="settings-panel" id="tab-stok">
                <div class="panel">
                    <div class="panel-head"><h2>Batas Minimum Stok</h2></div>
                    <p class="hint-text mb">Barang di bawah angka ini otomatis berstatus "Menipis" di dashboard.</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Batas Minimum Default</label>
                            <input type="number" value="10">
                        </div>
                        <div class="form-group">
                            <label>Batas Kritis (Habis akan diperingatkan)</label>
                            <input type="number" value="0">
                        </div>
                    </div>

                    <div class="toggle-row">
                        <div>
                            <div class="toggle-title">Gunakan batas minimum per kategori</div>
                            <div class="toggle-desc">Tiap kategori bisa punya ambang batas berbeda</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <button class="btn-primary">Simpan Pengaturan</button>
                </div>

                <div class="panel">
                    <div class="panel-head"><h2>Satuan & Penomoran</h2></div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Satuan Default</label>
                            <select>
                                <option>Pcs</option>
                                <option>Box</option>
                                <option>Kg</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Format Kode SKU</label>
                            <input type="text" value="SKU-0000">
                        </div>
                    </div>

                    <button class="btn-primary">Simpan Pengaturan</button>
                </div>
            </div>

            {{-- MANAJEMEN PENGGUNA --}}
            <div class="settings-panel" id="tab-pengguna">
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Pengguna</h2>
            <button class="btn-primary small" onclick="document.getElementById('modalTambahUser').classList.add('show')">+ Tambah Pengguna</button>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="th-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="tag tag-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="th-center">
                            <div class="table-actions">
                                <button class="btn-icon" title="Edit" onclick='bukaModalEditUser(@json($user))'>✎</button>
                                <form method="POST" action="{{ route('pengaturan.users.destroy', $user->id) }}" onsubmit="return confirm('Yakin hapus {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Hapus">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH USER --}}
<div class="modal-overlay" id="modalTambahUser">
    <div class="modal-box">
        <div class="modal-head">
            <h3>Tambah Pengguna</h3>
            <button type="button" class="modal-close" onclick="document.getElementById('modalTambahUser').classList.remove('show')">✕</button>
        </div>
        <form method="POST" action="{{ route('pengaturan.users.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="user">User</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary full">Simpan Pengguna</button>
        </form>
    </div>
</div>

        {{-- MODAL EDIT USER --}}
        <div class="modal-overlay" id="modalEditUser">
            <div class="modal-box">
                <div class="modal-head">
                    <h3>Edit Pengguna</h3>
                    <button type="button" class="modal-close" onclick="document.getElementById('modalEditUser').classList.remove('show')">✕</button>
                </div>
                <form method="POST" id="formEditUser">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" id="edit_user_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_user_email" required>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Role</label>
                            <select name="role" id="edit_user_role" required>
                                <option value="user">User</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary full">Update Pengguna</button>
                </form>
            </div>
        </div>

            {{-- NOTIFIKASI --}}
            <div class="settings-panel" id="tab-notifikasi">
                <div class="panel">
                    <div class="panel-head"><h2>Notifikasi Stok</h2></div>

                    <div class="toggle-row">
                        <div>
                            <div class="toggle-title">Notifikasi stok menipis</div>
                            <div class="toggle-desc">Dapat notifikasi saat barang mencapai batas minimum</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-row">
                        <div>
                            <div class="toggle-title">Notifikasi stok habis</div>
                            <div class="toggle-desc">Dapat notifikasi saat barang stoknya jadi 0</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-row">
                        <div>
                            <div class="toggle-title">Kirim juga lewat email</div>
                            <div class="toggle-desc">Selain notifikasi di aplikasi, kirim juga ke email</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Jadwal Ringkasan Harian</label>
                            <input type="time" value="08:00">
                        </div>
                    </div>

                    <button class="btn-primary">Simpan Pengaturan</button>
                </div>
            </div>

            {{-- DATA MASTER --}}
            <div class="settings-panel" id="tab-data-master">
                <div class="panel">
                    <div class="panel-head"><h2>Data Master</h2></div>
                    <p class="hint-text mb">Kelola data acuan yang dipakai di seluruh sistem.</p>

                    <div class="master-links">
                        <a href="{{ url('/gudang') }}" class="master-link">
                            <span class="master-icon">🏢</span>
                            <div>
                                <div class="master-title">Kelola Gudang</div>
                                <div class="master-desc">3 gudang terdaftar</div>
                            </div>
                            <span class="master-arrow">→</span>
                        </a>

                        <a href="{{ url('/kategori') }}" class="master-link">
                            <span class="master-icon">🗂</span>
                            <div>
                                <div class="master-title">Kelola Kategori</div>
                                <div class="master-desc">5 kategori terdaftar</div>
                            </div>
                            <span class="master-arrow">→</span>
                        </a>

                        <a href="#" class="master-link">
                            <span class="master-icon">🚚</span>
                            <div>
                                <div class="master-title">Kelola Supplier</div>
                                <div class="master-desc">Belum ada data</div>
                            </div>
                            <span class="master-arrow">→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- SISTEM & UMUM --}}
            <div class="settings-panel" id="tab-sistem">
                <div class="panel">
                    <div class="panel-head"><h2>Preferensi Umum</h2></div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Bahasa</label>
                            <select>
                                <option>Bahasa Indonesia</option>
                                <option>English</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Format Tanggal</label>
                            <select>
                                <option>DD/MM/YYYY</option>
                                <option>MM/DD/YYYY</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Zona Waktu</label>
                            <select>
                                <option>WIB (GMT+7)</option>
                                <option>WITA (GMT+8)</option>
                                <option>WIT (GMT+9)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mata Uang</label>
                            <select>
                                <option>IDR (Rp)</option>
                                <option>USD ($)</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn-primary">Simpan Pengaturan</button>
                </div>

                <div class="panel">
                    <div class="panel-head"><h2>Backup & Export</h2></div>

                    <div class="export-row">
                        <div>
                            <div class="toggle-title">Export Semua Data</div>
                            <div class="toggle-desc">Unduh seluruh data stok dalam format Excel</div>
                        </div>
                        <button class="btn-outline small">⭳ Export</button>
                    </div>
                </div>
            </div>

            {{-- KEAMANAN --}}
            <div class="settings-panel" id="tab-keamanan">
                <div class="panel">
                    <div class="panel-head"><h2>Riwayat Login</h2></div>

                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Perangkat</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="mono">24 Agu 2026, 08:12</td>
                                <td>Chrome - Windows</td>
                                <td>Jakarta, ID</td>
                                <td><span class="tag tag-ok">Sesi Ini</span></td>
                            </tr>
                            <tr>
                                <td class="mono">23 Agu 2026, 17:40</td>
                                <td>Chrome - Windows</td>
                                <td>Jakarta, ID</td>
                                <td><span class="tag tag-muted">Berakhir</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel">
                    <div class="panel-head"><h2>Sesi Aktif</h2></div>
                    <p class="hint-text mb">Keluar dari semua perangkat lain selain yang sedang kamu pakai sekarang.</p>
                    <button class="btn-outline small danger-text">Logout Semua Perangkat Lain</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));

            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });

    function bukaModalEditUser(user) {
        document.getElementById('formEditUser').action = '/pengaturan/users/' + user.id;
        document.getElementById('edit_user_name').value = user.name;
        document.getElementById('edit_user_email').value = user.email;
        document.getElementById('edit_user_role').value = user.role;
        document.getElementById('modalEditUser').classList.add('show');
    }
</script>
@endsection
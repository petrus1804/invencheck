@extends('layouts.app')

@section('title', 'Edit Profil - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('pengaturan.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Edit Profil</h1>
            <p>Kelola informasi akun dan keamanan kamu</p>
        </div>
    </div>

    <div class="profile-edit-layout">

        <div class="profile-edit-col">
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
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label>Role</label>
                        <input type="text" value="{{ ucfirst(auth()->user()->role) }}" disabled>
                    </div>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </form>
            </div>

            <div class="panel danger-zone">
                <div class="panel-head"><h2>Hapus Akun</h2></div>
                <p class="hint-text mb">Setelah akun dihapus, seluruh data terkait akun ini akan hilang permanen.</p>
                <button type="button" class="btn-outline small danger-text" onclick="document.getElementById('modalHapusAkun').classList.add('show')">Hapus Akun Saya</button>
            </div>
        </div>

        <div class="profile-edit-col">
            <div class="panel">
                <div class="panel-head"><h2>Ganti Password</h2></div>

                @if(session('status') === 'password-updated')
                    <div class="alert-success">Password berhasil diperbarui.</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label>Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••" required>
                        @error('current_password', 'updatePassword')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label>Password Baru</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                        @error('password', 'updatePassword')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary">Update Password</button>
                </form>
            </div>
        </div>

    </div>

    {{-- MODAL HAPUS AKUN --}}
    <div class="modal-overlay" id="modalHapusAkun">
        <div class="modal-box" style="max-width: 420px;">
            <div class="modal-head">
                <h3>Hapus Akun</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalHapusAkun').classList.remove('show')">✕</button>
            </div>

            <p class="hint-text mb">Masukkan password kamu untuk konfirmasi penghapusan akun. Tindakan ini tidak bisa dibatalkan.</p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @error('password', 'userDeletion')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn-primary full" style="background: var(--red);">Ya, Hapus Akun Saya</button>
            </form>
        </div>
    </div>

@endsection
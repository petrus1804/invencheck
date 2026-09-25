@extends('layouts.guest')

@section('title', 'Daftar - InvenCheck')

@section('content')

    <h1 class="auth-title">Buat Akun</h1>
    <p class="auth-subtitle">Daftar untuk mulai menggunakan InvenCheck</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama kamu">
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi password">
            @error('password_confirmation')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary full">Daftar</button>

        <p class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
        </p>
    </form>

@endsection
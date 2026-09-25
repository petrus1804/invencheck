@extends('layouts.guest')

@section('title', 'Login - InvenCheck')

@section('content')

    <h1 class="auth-title">Masuk</h1>
    <p class="auth-subtitle">Masuk ke akun InvenCheck kamu</p>

    @if (session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required placeholder="••••••••">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary full">Masuk</button>

        @if (Route::has('register'))
            <p class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a>
            </p>
        @endif
    </form>

@endsection
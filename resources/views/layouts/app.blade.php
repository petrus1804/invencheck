<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InvenCheck')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('app.css') }}">
    @yield('styles')
</head>
<body>

    <div class="app">

        @include('partials.sidebar')

        <div class="main">

            @include('partials.topbar')

            <main class="content">
                @yield('content')
            </main>

        </div>
    </div>

     @if(session('access_denied'))
        <div class="modal-overlay show" id="accessDeniedModal">
            <div class="modal-box" style="max-width: 380px; text-align: center;">
                <div style="font-size: 36px; margin-bottom: 12px;">🚫</div>
                <h3 style="margin-bottom: 8px;">Akses Ditolak</h3>
                <p class="hint-text mb">{{ session('access_denied') }}</p>
                <button class="btn-primary full" onclick="document.getElementById('accessDeniedModal').classList.remove('show')">Mengerti</button>
            </div>
        </div>
    @endif

    @yield('scripts')
</body>
</html>
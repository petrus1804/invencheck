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
    <link rel="stylesheet" href="{{ asset('auth.css') }}">
</head>
<body class="auth-body">

    <div class="auth-wrapper">

        <div class="auth-side">
            <div class="auth-brand">
                <img src="{{ asset('images/IC.png') }}" alt="InvenCheck" class="brand-mark">
                <div>
                    <div class="brand-name">InvenCheck</div>
                    <div class="brand-sub">Warehouse System</div>
                </div>
            </div>

            <div class="auth-side-content">
                <h2>Pantau stok gudang, kapan saja.</h2>
                <p>Kelola barang masuk, keluar, dan stok kritis dalam satu sistem yang rapi dan real-time.</p>
            </div>

        </div>

        <div class="auth-form-area">
            <div class="auth-card">
                @yield('content')
            </div>
        </div>

    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SILOG - Sistem Logistik Polres')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Copy styles dari landing page yang relevan */
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #dc2626;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Tambahkan style lainnya sesuai kebutuhan */
    </style>
    @stack('styles')
</head>
<body>
    <!-- Jika ingin header sama dengan landing page -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="{{ route('home') }}" class="logo-text" style="text-decoration: none; color: var(--primary);">
                        SILOG
                    </a>
                </div>
                <div class="auth-links">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="cta-button">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="cta-button">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-secondary" style="margin-left: 10px;">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Footer bisa disamakan dengan landing page -->
    <footer>
        <!-- Copy footer dari landing page -->
    </footer>

    @stack('scripts')
</body>
</html>
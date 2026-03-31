<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Karyawan HR')</title>

    {{-- !! Anti-flash: set theme SEBELUM CSS diload !! --}}
    <script>
        (function () {
            var theme = localStorage.getItem('hr-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Leaflet CSS (untuk halaman Check In) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('css/hr-app.css') }}" />

    {{-- Extra head per halaman --}}
    @stack('styles')
</head>
<body>
    <div class="app">

        {{-- Sidebar Overlay (mobile) --}}
        <div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

        {{-- Sidebar --}}
        @include('employee.layouts.__sidebar')

        {{-- Main Wrapper --}}
        <div class="main">

            {{-- Topbar / Header --}}
            @include('employee.layouts.__header')

            {{-- Page Content --}}
            <div class="content">
                @yield('content')
            </div>

            {{-- Footer --}}
            @include('employee.layouts.__footer')

        </div>{{-- /.main --}}

    </div>{{-- /.app --}}

    {{-- Toast Notification --}}
    <div class="toast" id="toast"></div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Global JS --}}
    <script src="{{ asset('js/hr-app.js') }}"></script>

    {{-- Extra scripts per halaman --}}
    @stack('scripts')
</body>
</html>
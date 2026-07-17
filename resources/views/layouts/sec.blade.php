<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $brand['library_name'] ?? config('app.name'))</title>
    @include('partials.brand-favicon')

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(config('branding.css_path')) }}">
    <link rel="stylesheet" href="{{ url('/branding/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}">
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

    @stack('styles')
    @yield('styles')
    @vite(['resources/css/app.css', 'resources/js/admin-shell.jsx'])
</head>
<body class="admin-shell-body">
    <div id="admin-shell-root"></div>

    <div id="admin-blade-content" hidden>
        @yield('content')
    </div>

    <script type="application/json" id="admin-shell-props">
        @json(\App\Support\AdminShell::pageProps(request()))
    </script>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>

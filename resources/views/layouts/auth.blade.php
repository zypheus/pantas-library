<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') — {{ $brand['school_name'] ?? config('app.name') }}</title>
    @include('partials.brand-favicon')
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(config('branding.css_path')) }}">
    <link rel="stylesheet" href="{{ url('/branding/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/page.css') }}">
    @stack('styles')
</head>
<body class="auth-page">
    @if(!empty($showStagingBanner))
        <div class="auth-page__staging-banner">Staging environment</div>
    @endif
    <div class="auth-page__shell">
        <div class="auth-page__top">
            <a href="{{ route('landing') }}" class="auth-page__brand">
                @if(!empty($brand['logo_compact_url']))
                    <div class="auth-page__logo-wrap">
                        <img src="{{ $brand['logo_compact_url'] }}" alt="" class="auth-page__logo">
                    </div>
                @endif
                <p class="auth-page__brand-title">{{ $brand['library_name'] }}</p>
                <p class="auth-page__brand-sub">{{ $brand['system_name'] }} · staff access</p>
            </a>
            <a href="{{ route('landing') }}" class="auth-page__home-link">← OPAC</a>
        </div>

        <div class="auth-page__card">
            @yield('content')
        </div>

        @hasSection('footer')
            @yield('footer')
        @endif
    </div>

    @stack('scripts')
</body>
</html>

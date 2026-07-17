<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance — {{ $brand['library_name'] ?? 'Library' }}</title>
    @include('partials.brand-favicon')
    <link rel="stylesheet" href="{{ asset(config('branding.css_path')) }}">
    <link rel="stylesheet" href="{{ url('/branding/theme.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: var(--brand-font-family, system-ui, sans-serif);
            background: var(--brand-page-bg, #f8fafc);
            color: var(--brand-text-dark, #333);
        }
        .card {
            max-width: 420px;
            padding: 2rem;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
            text-align: center;
        }
        img { max-height: 64px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        @if(!empty($brand['logo_url']))
            <img src="{{ $brand['logo_url'] }}" alt="">
        @endif
        <h1>{{ $brand['library_name'] ?? 'Library' }}</h1>
        <p>The system is temporarily under maintenance. Please check back soon.</p>
    </div>
</body>
</html>

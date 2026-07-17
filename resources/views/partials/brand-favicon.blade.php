@php
    $favicon = $brand['favicon_url'] ?? asset(config('branding.favicon', 'images/branding/favicon.svg'));
@endphp
<link rel="icon" href="{{ $favicon }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ $favicon }}">

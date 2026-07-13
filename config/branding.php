<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Branding stylesheet (per school)
    |--------------------------------------------------------------------------
    |
    | Path under /public. Copy branding.css.example → branding/branding.css locally.
    |
    */
    'css_path' => env('BRANDING_CSS', 'branding/branding.css'),

    /*
    |--------------------------------------------------------------------------
    | Display names
    |--------------------------------------------------------------------------
    */
    'school_name' => env('BRANDING_SCHOOL_NAME', env('APP_NAME', 'Library')),
    'library_name' => env('BRANDING_LIBRARY_NAME', 'Library'),
    'system_name' => env('BRANDING_SYSTEM_NAME', 'PANTAS'),
    'portal_subtitle' => env('BRANDING_PORTAL_SUBTITLE', 'Staff portal'),

    /*
    |--------------------------------------------------------------------------
    | Image assets (paths under /public/images)
    |--------------------------------------------------------------------------
    |
    | Per-school: replace files in images/branding/ or override via .env.
    | System defaults: images/system/ (shared placeholders).
    | Platform marketing (home page): images/platform/.
    |
    */
    'assets' => [
        'logo' => env('BRANDING_LOGO', 'images/branding/logo.svg'),
        'logo_landscape' => env('BRANDING_LOGO_LANDSCAPE', 'images/branding/logo-landscape.svg'),
        'logo_compact' => env('BRANDING_LOGO_COMPACT', 'images/branding/logo-compact.svg'),
        'favicon' => env('BRANDING_FAVICON', 'images/branding/favicon.svg'),
        'banner' => env('BRANDING_BANNER', 'images/branding/banner.svg'),
        'default_avatar' => env('BRANDING_DEFAULT_AVATAR', 'images/system/default-avatar.svg'),
        'default_book' => env('BRANDING_DEFAULT_BOOK', 'images/system/default-book.svg'),
        'partner_zendy' => env('BRANDING_PARTNER_ZENDY_LOGO', 'images/branding/partner-zendy.svg'),
        // Null when unset — Branding.php falls back to school logos (see resolveAsset).
        'platform_logo' => env('BRANDING_PLATFORM_LOGO'),
        'platform_logo_landscape' => env('BRANDING_PLATFORM_LOGO_LANDSCAPE'),
        'platform_vendor_logo' => env('BRANDING_PLATFORM_VENDOR_LOGO'),
    ],

    /*
    |--------------------------------------------------------------------------
    | External links
    |--------------------------------------------------------------------------
    */
    'links' => [
        'school_home' => env('BRANDING_SCHOOL_HOME_URL', '/'),
        'zendy' => env('BRANDING_ZENDY_URL', 'https://zendy.io'),
    ],

];

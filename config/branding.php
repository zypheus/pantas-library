<?php

/**
 * Per-deployment branding defaults.
 * Published site_settings override these at runtime via Branding resolver.
 */
return [

    'css_path' => env('BRANDING_CSS', 'branding/branding.css'),

    'school_name' => env('BRANDING_SCHOOL_NAME', 'University School'),
    'library_name' => env('BRANDING_LIBRARY_NAME', 'Library'),
    'system_name' => env('BRANDING_SYSTEM_NAME', env('APP_NAME', 'PANTAS')),
    'staff_portal_subtitle' => env('BRANDING_STAFF_PORTAL_SUBTITLE', 'Staff Portal'),

    'school_home_url' => env('BRANDING_SCHOOL_HOME_URL', '/'),
    'external_resource_url' => env('BRANDING_EXTERNAL_RESOURCE_URL', 'https://zendy.io'),

    'logo' => env('BRANDING_LOGO', 'images/branding/logo.svg'),
    'logo_landscape' => env('BRANDING_LOGO_LANDSCAPE', 'images/branding/logo-landscape.svg'),
    'logo_compact' => env('BRANDING_LOGO_COMPACT', 'images/branding/logo-compact.svg'),
    'favicon' => env('BRANDING_FAVICON', 'images/branding/favicon.svg'),
    'banner' => env('BRANDING_BANNER', 'images/branding/banner.svg'),
    'partner_logo' => env('BRANDING_PARTNER_LOGO', 'images/branding/partner-zendy.svg'),
    'default_book' => env('BRANDING_DEFAULT_BOOK', 'images/branding/logo.svg'),

    /*
    | Default theme tokens (CSS custom properties without the -- prefix).
    | Used when no published site_settings theme exists.
    */
    'tokens' => [
        'brand-primary' => '#ffd700',
        'brand-accent' => '#4caf50',
        'brand-blue' => '#1f4ea7',
        'brand-blue-dark' => '#163a82',
        'brand-green-dark' => '#2e7d32',
        'brand-text-dark' => '#333333',
        'brand-text-light' => '#ffffff',
        'brand-page-bg' => '#ffffff',
        'brand-school-name-color' => '#1f4ea7',
        'brand-nav-link' => '#1f4ea7',
        'brand-nav-link-active' => '#ffffff',
        'brand-button-bg' => '#4caf50',
        'brand-button-text' => '#ffffff',
        'brand-button-hover-bg' => '#2e7d32',
        'brand-button-hover-text' => '#ffffff',
        'brand-button-primary-bg' => '#4caf50',
        'brand-button-primary-text' => '#ffffff',
        'brand-button-primary-hover-bg' => '#2e7d32',
        'brand-button-secondary-bg' => '#64748b',
        'brand-button-secondary-text' => '#ffffff',
        'brand-button-secondary-hover-bg' => '#475569',
        'brand-table-header-bg' => '#1f4ea7',
        'brand-table-header-text' => '#ffffff',
        'brand-table-row-bg' => '#ffffff',
        'brand-table-row-alt-bg' => '#f8fafc',
        'brand-table-row-hover-bg' => '#eef2ff',
        'brand-table-row-selected-bg' => '#dbeafe',
        'brand-table-footer-bg' => '#f1f5f9',
        'brand-table-border' => '#e5e7eb',
        'brand-table-text' => '#333333',
        'brand-footer-bg' => '#1f4ea7',
        'brand-logout-bg' => '#4caf50',
        'brand-logout-text' => '#ffffff',
        'brand-danger-bg' => '#dc2626',
        'brand-danger-text' => '#ffffff',
        'brand-success-bg' => '#16a34a',
        'brand-success-text' => '#ffffff',
        'brand-kiosk-gradient-from' => '#1f4ea7',
        'brand-kiosk-gradient-to' => '#2e7d32',
        'brand-sidebar-bg' => '#4caf50',
        'brand-sidebar-text' => '#ffffff',
        'brand-shell-background' => '#ffffff',
        'brand-shell-button-bg' => '#4caf50',
        'brand-shell-button-text' => '#ffffff',
        'brand-font-family' => "'Poppins', ui-sans-serif, system-ui, sans-serif",
        'brand-font-family-heading' => "'Poppins', ui-sans-serif, system-ui, sans-serif",
        'brand-font-family-mono' => "ui-monospace, 'Cascadia Code', Consolas, monospace",
    ],

    'landing_page' => [
        'hero_kicker' => 'Online Public Access Catalog',
        'hero_heading' => 'Find books and resources',
        'hero_subtitle' => 'Search the library collection by title, author, ISBN, or subject.',
        'search_placeholder' => 'Search books…',
        'search_button_label' => 'Search',
        'helper_text' => 'Tip: use quotes for exact phrases.',
        'new_arrivals_title' => 'New arrivals',
        'new_arrivals_description' => 'Recently added titles in the collection.',
        'external_links_title' => 'External resources',
        'external_links_description' => 'Partner databases and research tools.',
        'hero_background' => null,
        'sections_visible' => [
            'hero' => true,
            'new_arrivals' => true,
            'external_links' => true,
        ],
        'sections_order' => ['hero', 'new_arrivals', 'external_links'],
    ],

    'feature_flags' => [
        'maintenance_mode' => false,
        'show_staging_banner' => false,
        'opac_room_booking_link' => true,
        'kiosk_logout_feedback' => true,
        'experimental_inertia_ui' => false,
    ],
];

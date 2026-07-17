<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Branding
{
    public const CACHE_KEY = 'branding.published';

    public const THEME_CACHE_KEY = 'branding.theme_css';

    /**
     * @return array<string, mixed>
     */
    public static function forViews(): array
    {
        $resolved = self::resolved();

        return [
            'school_name' => $resolved['school_name'],
            'library_name' => $resolved['library_name'],
            'system_name' => $resolved['system_name'],
            'staff_portal_subtitle' => $resolved['staff_portal_subtitle'],
            'school_home_url' => $resolved['school_home_url'],
            'external_resource_url' => $resolved['external_resource_url'],
            'logo_url' => asset($resolved['logo']),
            'logo_landscape_url' => asset($resolved['logo_landscape']),
            'logo_compact_url' => asset($resolved['logo_compact']),
            'favicon_url' => asset($resolved['favicon']),
            'banner_url' => asset($resolved['banner']),
            'partner_logo_url' => asset($resolved['partner_logo']),
            'partner_zendy_url' => asset($resolved['partner_logo']),
            'default_book_url' => asset($resolved['default_book']),
            'theme_css_url' => url('/branding/theme.css'),
            'zendy_url' => $resolved['external_resource_url'] ?: 'https://zendy.io',
            'favicon_mime' => 'image/svg+xml',
            'version' => $resolved['version'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function forInertia(): array
    {
        return self::forViews();
    }

    /**
     * @return array<string, mixed>
     */
    public static function forShell(): array
    {
        $views = self::forViews();

        return [
            'schoolName' => $views['school_name'],
            'libraryName' => $views['library_name'],
            'systemName' => $views['system_name'],
            'staffPortalSubtitle' => $views['staff_portal_subtitle'],
            'logoUrl' => $views['logo_url'],
            'logoCompactUrl' => $views['logo_compact_url'],
            'faviconUrl' => $views['favicon_url'],
            'bannerUrl' => $views['banner_url'],
            'themeCssUrl' => $views['theme_css_url'],
            'version' => $views['version'],
        ];
    }

    /**
     * Resolve branding identity + asset paths.
     * Precedence: published DB → config → built-in defaults.
     *
     * @return array<string, mixed>
     */
    public static function resolved(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), function () {
            $config = config('branding', []);
            $published = self::publishedPayload();

            $branding = array_merge(
                [
                    'school_name' => $config['school_name'] ?? 'University School',
                    'library_name' => $config['library_name'] ?? 'Library',
                    'system_name' => $config['system_name'] ?? config('app.name', 'PANTAS'),
                    'staff_portal_subtitle' => $config['staff_portal_subtitle'] ?? 'Staff Portal',
                    'school_home_url' => $config['school_home_url'] ?? '/',
                    'external_resource_url' => $config['external_resource_url'] ?? '',
                    'logo' => $config['logo'] ?? 'images/branding/logo.svg',
                    'logo_landscape' => $config['logo_landscape'] ?? 'images/branding/logo-landscape.svg',
                    'logo_compact' => $config['logo_compact'] ?? 'images/branding/logo-compact.svg',
                    'favicon' => $config['favicon'] ?? 'images/branding/favicon.svg',
                    'banner' => $config['banner'] ?? 'images/branding/banner.svg',
                    'partner_logo' => $config['partner_logo'] ?? 'images/branding/partner-zendy.svg',
                    'default_book' => $config['default_book'] ?? 'images/branding/logo.svg',
                ],
                is_array($published['branding'] ?? null) ? $published['branding'] : []
            );

            return array_merge($branding, [
                'tokens' => self::resolveTokens($published),
                'landing_page' => self::resolveLandingPage($published),
                'feature_flags' => self::resolveFeatureFlags($published),
                'version' => (int) ($published['version'] ?? 0),
                'published_at' => $published['published_at'] ?? null,
            ]);
        });
    }

    /**
     * @return array<string, string>
     */
    public static function tokens(): array
    {
        return self::resolved()['tokens'] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function landingPage(): array
    {
        return self::resolved()['landing_page'] ?? [];
    }

    /**
     * @return array<string, bool>
     */
    public static function featureFlags(): array
    {
        return self::resolved()['feature_flags'] ?? [];
    }

    public static function featureEnabled(string $flag): bool
    {
        $flags = self::featureFlags();

        return (bool) ($flags[$flag] ?? false);
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::THEME_CACHE_KEY);
    }

    /**
     * @return array<string, mixed>
     */
    protected static function publishedPayload(): array
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return [];
            }

            $row = SiteSetting::query()
                ->where('group', SiteSetting::GROUP_APPEARANCE)
                ->whereNotNull('published_at')
                ->orderByDesc('version')
                ->first();

            if (! $row) {
                return [];
            }

            $published = is_array($row->published) ? $row->published : [];

            return array_merge($published, [
                'version' => $row->version,
                'published_at' => optional($row->published_at)?->toIso8601String(),
            ]);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @param  array<string, mixed>  $published
     * @return array<string, string>
     */
    protected static function resolveTokens(array $published): array
    {
        $defaults = config('branding.tokens', []);
        $fromTheme = is_array($published['theme'] ?? null) ? $published['theme'] : [];
        $fromButtons = is_array($published['buttons'] ?? null) ? $published['buttons'] : [];
        $fromTables = is_array($published['tables'] ?? null) ? $published['tables'] : [];

        return array_merge($defaults, $fromTheme, $fromButtons, $fromTables);
    }

    /**
     * @param  array<string, mixed>  $published
     * @return array<string, mixed>
     */
    protected static function resolveLandingPage(array $published): array
    {
        $defaults = config('branding.landing_page', []);
        $override = is_array($published['landing_page'] ?? null) ? $published['landing_page'] : [];

        return array_replace_recursive($defaults, $override);
    }

    /**
     * @param  array<string, mixed>  $published
     * @return array<string, bool>
     */
    protected static function resolveFeatureFlags(array $published): array
    {
        $defaults = config('branding.feature_flags', []);
        $override = is_array($published['feature_flags'] ?? null) ? $published['feature_flags'] : [];

        $merged = array_merge($defaults, $override);

        return array_map(fn ($v) => (bool) $v, $merged);
    }
}

<?php

namespace App\Services;

use App\Models\AdminActivity;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\Branding;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AppearanceManager
{
    /** @var list<string> */
    public const ALLOWED_COLOR_KEYS = [
        'brand-primary',
        'brand-accent',
        'brand-blue',
        'brand-blue-dark',
        'brand-green-dark',
        'brand-text-dark',
        'brand-text-light',
        'brand-page-bg',
        'brand-school-name-color',
        'brand-nav-link',
        'brand-nav-link-active',
        'brand-button-bg',
        'brand-button-text',
        'brand-button-hover-bg',
        'brand-button-hover-text',
        'brand-button-primary-bg',
        'brand-button-primary-text',
        'brand-button-primary-hover-bg',
        'brand-button-secondary-bg',
        'brand-button-secondary-text',
        'brand-button-secondary-hover-bg',
        'brand-table-header-bg',
        'brand-table-header-text',
        'brand-table-row-bg',
        'brand-table-row-alt-bg',
        'brand-table-row-hover-bg',
        'brand-table-row-selected-bg',
        'brand-table-footer-bg',
        'brand-table-border',
        'brand-table-text',
        'brand-footer-bg',
        'brand-logout-bg',
        'brand-logout-text',
        'brand-danger-bg',
        'brand-danger-text',
        'brand-success-bg',
        'brand-success-text',
        'brand-kiosk-gradient-from',
        'brand-kiosk-gradient-to',
        'brand-sidebar-bg',
        'brand-sidebar-text',
        'brand-shell-background',
        'brand-shell-button-bg',
        'brand-shell-button-text',
    ];

    /** @var list<string> */
    public const ALLOWED_FONT_KEYS = [
        'brand-font-family',
        'brand-font-family-heading',
        'brand-font-family-mono',
    ];

    /** @var list<string> */
    public const BRANDING_TEXT_KEYS = [
        'school_name',
        'library_name',
        'system_name',
        'staff_portal_subtitle',
        'school_home_url',
        'external_resource_url',
    ];

    /** @var list<string> */
    public const BRANDING_ASSET_KEYS = [
        'logo',
        'logo_landscape',
        'logo_compact',
        'favicon',
        'banner',
        'partner_logo',
        'default_book',
    ];

    public function working(): SiteSetting
    {
        return SiteSetting::appearanceWorking();
    }

    /**
     * Effective draft for the editor (defaults merged under draft overrides).
     *
     * @return array{setting: SiteSetting, draft: array<string, mixed>, published: array<string, mixed>, defaults: array<string, mixed>, effective: array<string, mixed>}
     */
    public function editorState(): array
    {
        $setting = $this->working();
        $defaults = SiteSetting::defaultsFromConfig();
        $draft = is_array($setting->draft) ? $setting->draft : SiteSetting::emptyPayload();
        $published = is_array($setting->published) ? $setting->published : [];

        return [
            'setting' => $setting,
            'defaults' => $defaults,
            'draft' => $draft,
            'published' => $published,
            'effective' => $this->mergeEffective($defaults, $draft),
            'has_draft_changes' => $setting->hasDraftChanges(),
            'version' => $setting->version,
            'published_at' => optional($setting->published_at)?->toIso8601String(),
            'draft_updated_at' => optional($setting->draft_updated_at)?->toIso8601String(),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function saveDraft(array $input, ?User $user = null): SiteSetting
    {
        $setting = $this->working();
        $defaults = SiteSetting::defaultsFromConfig();
        $current = is_array($setting->draft) ? $setting->draft : SiteSetting::emptyPayload();

        $next = [
            'branding' => $this->sanitizeBranding(array_merge(
                $current['branding'] ?? [],
                is_array($input['branding'] ?? null) ? $input['branding'] : []
            )),
            'landing_page' => $this->sanitizeLandingPage(array_merge(
                $current['landing_page'] ?? [],
                is_array($input['landing_page'] ?? null) ? $input['landing_page'] : []
            )),
            'buttons' => $this->sanitizeColorMap(array_merge(
                $current['buttons'] ?? [],
                is_array($input['buttons'] ?? null) ? $input['buttons'] : []
            ), 'brand-button'),
            'tables' => $this->sanitizeColorMap(array_merge(
                $current['tables'] ?? [],
                is_array($input['tables'] ?? null) ? $input['tables'] : []
            ), 'brand-table'),
            'theme' => $this->sanitizeTheme(array_merge(
                $current['theme'] ?? [],
                is_array($input['theme'] ?? null) ? $input['theme'] : []
            )),
            'feature_flags' => $this->sanitizeFeatureFlags(array_merge(
                $defaults['feature_flags'] ?? [],
                $current['feature_flags'] ?? [],
                is_array($input['feature_flags'] ?? null) ? $input['feature_flags'] : []
            )),
        ];

        $setting->fill([
            'draft' => $next,
            'draft_edited_by' => $user?->id,
            'draft_updated_at' => now(),
        ])->save();

        AdminActivityLogger::log(
            AdminActivity::TYPE_SETTINGS,
            'Appearance draft saved',
            'Developer saved branding draft v'.($setting->version),
            route('developer.branding'),
            'settings',
            $setting,
            $user?->id,
        );

        return $setting->fresh();
    }

    public function publish(?User $user = null): SiteSetting
    {
        $setting = $this->working();
        $draft = is_array($setting->draft) ? $setting->draft : SiteSetting::emptyPayload();

        $newVersion = max(1, (int) $setting->version + 1);

        // Keep history: create a new version row when publishing from an already-published version.
        if ($setting->published_at && $setting->version > 0) {
            $setting = SiteSetting::create([
                'group' => SiteSetting::GROUP_APPEARANCE,
                'version' => $newVersion,
                'draft' => $draft,
                'published' => $draft,
                'draft_edited_by' => $user?->id,
                'published_by' => $user?->id,
                'draft_updated_at' => now(),
                'published_at' => now(),
            ]);
        } else {
            $setting->fill([
                'version' => $newVersion,
                'published' => $draft,
                'published_by' => $user?->id,
                'published_at' => now(),
            ])->save();
        }

        Branding::forgetCache();

        AdminActivityLogger::log(
            AdminActivity::TYPE_SETTINGS,
            'Appearance published',
            'Published branding version '.$setting->version,
            route('developer.branding'),
            'settings',
            $setting,
            $user?->id,
        );

        return $setting->fresh();
    }

    public function discardDraft(?User $user = null): SiteSetting
    {
        $setting = $this->working();
        $setting->fill([
            'draft' => $setting->published ?? SiteSetting::emptyPayload(),
            'draft_edited_by' => $user?->id,
            'draft_updated_at' => now(),
        ])->save();

        AdminActivityLogger::log(
            AdminActivity::TYPE_SETTINGS,
            'Appearance draft discarded',
            'Draft reset to published values',
            route('developer.branding'),
            'settings',
            $setting,
            $user?->id,
        );

        return $setting->fresh();
    }

    public function resetToDefaults(?User $user = null): SiteSetting
    {
        $setting = $this->working();
        $empty = SiteSetting::emptyPayload();

        $newVersion = max(1, (int) $setting->version + 1);

        $setting = SiteSetting::create([
            'group' => SiteSetting::GROUP_APPEARANCE,
            'version' => $newVersion,
            'draft' => $empty,
            'published' => $empty,
            'draft_edited_by' => $user?->id,
            'published_by' => $user?->id,
            'draft_updated_at' => now(),
            'published_at' => now(),
        ]);

        Branding::forgetCache();

        AdminActivityLogger::log(
            AdminActivity::TYPE_SETTINGS,
            'Appearance reset to defaults',
            'Reset to config/env fallbacks (version '.$setting->version.')',
            route('developer.branding'),
            'settings',
            $setting,
            $user?->id,
        );

        return $setting;
    }

    /**
     * Store an uploaded branding asset under public/images/branding/managed/.
     */
    public function storeAsset(UploadedFile $file, string $key, ?User $user = null): string
    {
        if (! in_array($key, self::BRANDING_ASSET_KEYS, true)) {
            throw new \InvalidArgumentException('Unknown branding asset key.');
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico'];
        if (! in_array($ext, $allowed, true)) {
            throw new \InvalidArgumentException('Unsupported image type.');
        }

        $filename = $key.'-'.Str::lower(Str::random(10)).'.'.$ext;
        $relativeDir = 'images/branding/managed';
        $absoluteDir = public_path($relativeDir);

        if (! is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }

        $file->move($absoluteDir, $filename);
        $relative = $relativeDir.'/'.$filename;

        $setting = $this->working();
        $draft = is_array($setting->draft) ? $setting->draft : SiteSetting::emptyPayload();
        $draft['branding'] = array_merge($draft['branding'] ?? [], [$key => $relative]);

        $setting->fill([
            'draft' => $draft,
            'draft_edited_by' => $user?->id,
            'draft_updated_at' => now(),
        ])->save();

        return $relative;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function versionHistory(int $limit = 20): array
    {
        return SiteSetting::query()
            ->where('group', SiteSetting::GROUP_APPEARANCE)
            ->whereNotNull('published_at')
            ->orderByDesc('version')
            ->limit($limit)
            ->with('publisher:id,fname,lname,email')
            ->get()
            ->map(fn (SiteSetting $row) => [
                'id' => $row->id,
                'version' => $row->version,
                'published_at' => optional($row->published_at)?->timezone('Asia/Manila')->toDateTimeString(),
                'publisher' => $row->publisher?->fullName(),
                'is_current' => $row->id === $this->working()->id,
            ])
            ->all();
    }

    public function rollbackToVersion(int $version, ?User $user = null): SiteSetting
    {
        $historical = SiteSetting::query()
            ->where('group', SiteSetting::GROUP_APPEARANCE)
            ->where('version', $version)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $payload = is_array($historical->published) ? $historical->published : SiteSetting::emptyPayload();

        $setting = $this->working();
        $setting->fill([
            'draft' => $payload,
            'draft_edited_by' => $user?->id,
            'draft_updated_at' => now(),
        ])->save();

        return $this->publish($user);
    }

    /**
     * Export published (or draft) theme as JSON-serializable array.
     *
     * @return array<string, mixed>
     */
    public function exportBundle(bool $useDraft = false): array
    {
        $state = $this->editorState();

        return [
            'format' => 'pantas-theme-v1',
            'exported_at' => now()->toIso8601String(),
            'version' => $state['version'],
            'payload' => $useDraft ? $state['draft'] : ($state['published'] ?: $state['draft']),
        ];
    }

    /**
     * @param  array<string, mixed>  $bundle
     */
    public function importBundle(array $bundle, ?User $user = null): SiteSetting
    {
        if (($bundle['format'] ?? null) !== 'pantas-theme-v1') {
            throw new \InvalidArgumentException('Unsupported theme bundle format.');
        }

        $payload = is_array($bundle['payload'] ?? null) ? $bundle['payload'] : [];

        return $this->saveDraft($payload, $user);
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @param  array<string, mixed>  $override
     * @return array<string, mixed>
     */
    protected function mergeEffective(array $defaults, array $override): array
    {
        return [
            'branding' => array_merge($defaults['branding'] ?? [], $override['branding'] ?? []),
            'landing_page' => array_replace_recursive($defaults['landing_page'] ?? [], $override['landing_page'] ?? []),
            'buttons' => array_merge($defaults['buttons'] ?? [], $override['buttons'] ?? []),
            'tables' => array_merge($defaults['tables'] ?? [], $override['tables'] ?? []),
            'theme' => array_merge($defaults['theme'] ?? [], $override['theme'] ?? []),
            'feature_flags' => array_merge($defaults['feature_flags'] ?? [], $override['feature_flags'] ?? []),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    protected function sanitizeBranding(array $input): array
    {
        $out = [];

        foreach (self::BRANDING_TEXT_KEYS as $key) {
            if (! array_key_exists($key, $input)) {
                continue;
            }
            $value = strip_tags((string) $input[$key]);
            $out[$key] = mb_substr(trim($value), 0, 255);
        }

        foreach (self::BRANDING_ASSET_KEYS as $key) {
            if (! array_key_exists($key, $input) || $input[$key] === null || $input[$key] === '') {
                continue;
            }
            $path = ltrim(str_replace('\\', '/', (string) $input[$key]), '/');
            if (preg_match('#^(images/branding/|branding/)#', $path)) {
                $out[$key] = $path;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    protected function sanitizeLandingPage(array $input): array
    {
        $textKeys = [
            'hero_kicker', 'hero_heading', 'hero_subtitle', 'search_placeholder',
            'search_button_label', 'helper_text', 'new_arrivals_title', 'new_arrivals_description',
            'external_links_title', 'external_links_description',
        ];

        $out = [];
        foreach ($textKeys as $key) {
            if (! array_key_exists($key, $input)) {
                continue;
            }
            $out[$key] = mb_substr(strip_tags((string) $input[$key]), 0, 500);
        }

        if (isset($input['sections_visible']) && is_array($input['sections_visible'])) {
            $out['sections_visible'] = [];
            foreach ($input['sections_visible'] as $section => $visible) {
                $out['sections_visible'][(string) $section] = (bool) $visible;
            }
        }

        if (isset($input['sections_order']) && is_array($input['sections_order'])) {
            $out['sections_order'] = array_values(array_map('strval', $input['sections_order']));
        }

        if (array_key_exists('hero_background', $input) && $input['hero_background']) {
            $path = ltrim(str_replace('\\', '/', (string) $input['hero_background']), '/');
            if (preg_match('#^(images/branding/|branding/)#', $path)) {
                $out['hero_background'] = $path;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, string>
     */
    protected function sanitizeColorMap(array $input, string $prefix): array
    {
        $out = [];
        foreach ($input as $key => $value) {
            $key = (string) $key;
            if (! str_starts_with($key, $prefix) && ! in_array($key, self::ALLOWED_COLOR_KEYS, true)) {
                continue;
            }
            $normalized = $this->normalizeColor((string) $value);
            if ($normalized !== null) {
                $out[$key] = $normalized;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, string>
     */
    protected function sanitizeTheme(array $input): array
    {
        $out = [];
        foreach ($input as $key => $value) {
            $key = (string) $key;
            if (in_array($key, self::ALLOWED_FONT_KEYS, true)) {
                $out[$key] = mb_substr(preg_replace('/[^\w\s\',\-\.]/', '', (string) $value) ?? '', 0, 200);
                continue;
            }
            if (! in_array($key, self::ALLOWED_COLOR_KEYS, true)) {
                continue;
            }
            $normalized = $this->normalizeColor((string) $value);
            if ($normalized !== null) {
                $out[$key] = $normalized;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, bool>
     */
    protected function sanitizeFeatureFlags(array $input): array
    {
        $allowed = array_keys(config('branding.feature_flags', []));
        $out = [];
        foreach ($allowed as $flag) {
            if (array_key_exists($flag, $input)) {
                $out[$flag] = filter_var($input[$flag], FILTER_VALIDATE_BOOLEAN);
            }
        }

        return $out;
    }

    public function normalizeColor(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $value)) {
            return strtolower($value);
        }
        if (preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(?:\s*,\s*(?:0|1|0?\.\d+))?\s*\)$/', $value)) {
            return $value;
        }

        return null;
    }
}

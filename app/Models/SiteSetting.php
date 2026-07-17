<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends Model
{
    public const GROUP_APPEARANCE = 'appearance';

    public const SETTING_GROUPS = [
        'branding',
        'landing_page',
        'buttons',
        'tables',
        'theme',
        'feature_flags',
    ];

    protected $fillable = [
        'group',
        'version',
        'draft',
        'published',
        'draft_edited_by',
        'published_by',
        'draft_updated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'draft' => 'array',
            'published' => 'array',
            'draft_updated_at' => 'datetime',
            'published_at' => 'datetime',
            'version' => 'integer',
        ];
    }

    public function draftEditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'draft_edited_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function hasDraftChanges(): bool
    {
        return json_encode($this->draft ?? []) !== json_encode($this->published ?? []);
    }

    /**
     * Working row for the appearance group (latest version, or a new empty draft).
     */
    public static function appearanceWorking(): self
    {
        $latest = static::query()
            ->where('group', self::GROUP_APPEARANCE)
            ->orderByDesc('version')
            ->orderByDesc('id')
            ->first();

        if ($latest) {
            return $latest;
        }

        return static::create([
            'group' => self::GROUP_APPEARANCE,
            'version' => 0,
            'draft' => static::emptyPayload(),
            'published' => null,
            'draft_updated_at' => now(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function emptyPayload(): array
    {
        return [
            'branding' => [],
            'landing_page' => [],
            'buttons' => [],
            'tables' => [],
            'theme' => [],
            'feature_flags' => [],
        ];
    }

    /**
     * Defaults merged from config for the editor UI.
     *
     * @return array<string, mixed>
     */
    public static function defaultsFromConfig(): array
    {
        return [
            'branding' => [
                'school_name' => config('branding.school_name'),
                'library_name' => config('branding.library_name'),
                'system_name' => config('branding.system_name'),
                'staff_portal_subtitle' => config('branding.staff_portal_subtitle'),
                'school_home_url' => config('branding.school_home_url'),
                'external_resource_url' => config('branding.external_resource_url'),
                'logo' => config('branding.logo'),
                'logo_landscape' => config('branding.logo_landscape'),
                'logo_compact' => config('branding.logo_compact'),
                'favicon' => config('branding.favicon'),
                'banner' => config('branding.banner'),
                'partner_logo' => config('branding.partner_logo'),
                'default_book' => config('branding.default_book'),
            ],
            'landing_page' => config('branding.landing_page', []),
            'buttons' => array_filter(
                config('branding.tokens', []),
                fn ($_, $key) => str_starts_with((string) $key, 'brand-button'),
                ARRAY_FILTER_USE_BOTH
            ),
            'tables' => array_filter(
                config('branding.tokens', []),
                fn ($_, $key) => str_starts_with((string) $key, 'brand-table'),
                ARRAY_FILTER_USE_BOTH
            ),
            'theme' => config('branding.tokens', []),
            'feature_flags' => config('branding.feature_flags', []),
        ];
    }
}

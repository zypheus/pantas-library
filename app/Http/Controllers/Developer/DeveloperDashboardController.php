<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Services\AppearanceManager;
use App\Support\Branding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DeveloperDashboardController extends Controller
{
    public function __construct(private AppearanceManager $appearance)
    {
    }

    public function dashboard(): Response
    {
        $state = $this->appearance->editorState();
        $resolved = Branding::resolved();

        return Inertia::render('Developer/Dashboard', [
            'overview' => [
                'version' => $resolved['version'] ?? 0,
                'published_at' => $resolved['published_at'] ?? null,
                'has_draft_changes' => $state['has_draft_changes'],
                'school_name' => $resolved['school_name'] ?? null,
                'library_name' => $resolved['library_name'] ?? null,
                'feature_flags' => $resolved['feature_flags'] ?? [],
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'storage_writable' => is_writable(public_path('images/branding')),
                'cache_driver' => config('cache.default'),
            ],
        ]);
    }

    public function branding(): Response
    {
        return Inertia::render('Developer/Branding', $this->editorProps());
    }

    public function colors(): Response
    {
        return Inertia::render('Developer/Colors', $this->editorProps());
    }

    public function typography(): Response
    {
        return Inertia::render('Developer/Typography', $this->editorProps());
    }

    public function landing(): Response
    {
        return Inertia::render('Developer/Landing', $this->editorProps());
    }

    public function featureFlags(): Response
    {
        return Inertia::render('Developer/FeatureFlags', $this->editorProps());
    }

    public function packages(): Response
    {
        return Inertia::render('Developer/Packages', array_merge($this->editorProps(), [
            'history' => $this->appearance->versionHistory(),
            'presets' => $this->presets(),
        ]));
    }

    public function designSystem(): Response
    {
        return Inertia::render('Developer/DesignSystem', [
            'tokens' => Branding::tokens(),
            'branding' => Branding::forInertia(),
        ]);
    }

    public function system(): Response
    {
        return Inertia::render('Developer/System', [
            'health' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'app_env' => config('app.env'),
                'app_debug' => (bool) config('app.debug'),
                'cache_driver' => config('cache.default'),
                'queue_connection' => config('queue.default'),
                'storage_writable' => is_writable(storage_path()),
                'branding_dir_writable' => is_writable(public_path('images/branding')),
                'theme_etag' => \App\Support\ThemeCss::etag(),
                'branding_version' => Branding::resolved()['version'] ?? 0,
            ],
        ]);
    }

    public function saveDraft(Request $request)
    {
        $payload = $request->validate([
            'branding' => 'sometimes|array',
            'landing_page' => 'sometimes|array',
            'buttons' => 'sometimes|array',
            'tables' => 'sometimes|array',
            'theme' => 'sometimes|array',
            'feature_flags' => 'sometimes|array',
        ]);

        $this->appearance->saveDraft($payload, $request->user());

        return back()->with('success', 'Draft saved. Publish when ready.');
    }

    public function publish(Request $request)
    {
        $this->appearance->publish($request->user());

        return back()->with('success', 'Appearance published. Theme CSS cache refreshed.');
    }

    public function discardDraft(Request $request)
    {
        $this->appearance->discardDraft($request->user());

        return back()->with('success', 'Draft discarded.');
    }

    public function reset(Request $request)
    {
        $this->appearance->resetToDefaults($request->user());

        return back()->with('success', 'Reset to configuration defaults and published.');
    }

    public function uploadAsset(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|in:logo,logo_landscape,logo_compact,favicon,banner,partner_logo,default_book',
            'file' => 'required|file|max:4096|mimes:png,jpg,jpeg,gif,svg,webp,ico',
        ]);

        $path = $this->appearance->storeAsset(
            $validated['file'],
            $validated['key'],
            $request->user()
        );

        return back()->with('success', 'Asset uploaded into draft: '.$path);
    }

    public function export(Request $request): StreamedResponse
    {
        $useDraft = $request->boolean('draft');
        $bundle = $this->appearance->exportBundle($useDraft);
        $filename = 'pantas-theme-v'.($bundle['version'] ?? 0).'.json';

        return response()->streamDownload(function () use ($bundle) {
            echo json_encode($bundle, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'bundle' => 'required|file|max:2048|mimes:json,txt',
        ]);

        $raw = file_get_contents($validated['bundle']->getRealPath());
        $decoded = json_decode($raw ?: '', true);

        if (! is_array($decoded)) {
            return back()->with('error', 'Invalid JSON theme bundle.');
        }

        try {
            $this->appearance->importBundle($decoded, $request->user());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Theme bundle imported as draft. Review and publish.');
    }

    public function applyPreset(Request $request)
    {
        $validated = $request->validate([
            'preset' => 'required|string|in:usm_green,neutral,high_contrast',
        ]);

        $presets = $this->presets();
        $payload = $presets[$validated['preset']]['payload'] ?? null;

        if (! is_array($payload)) {
            return back()->with('error', 'Unknown preset.');
        }

        $this->appearance->saveDraft($payload, $request->user());

        return back()->with('success', 'Preset applied to draft: '.$presets[$validated['preset']]['label']);
    }

    public function rollback(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|integer|min:1',
        ]);

        $this->appearance->rollbackToVersion((int) $validated['version'], $request->user());

        return back()->with('success', 'Rolled back and published version '.$validated['version']);
    }

    public function clearCaches(Request $request)
    {
        Branding::forgetCache();
        Cache::flush();

        try {
            Artisan::call('view:clear');
            Artisan::call('config:clear');
        } catch (\Throwable) {
            // Shared hosts may restrict artisan; branding cache already cleared.
        }

        return back()->with('success', 'Caches cleared (branding, application, views, config).');
    }

    public function healthJson(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'branding_version' => Branding::resolved()['version'] ?? 0,
            'theme_etag' => \App\Support\ThemeCss::etag(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function editorProps(): array
    {
        $state = $this->appearance->editorState();

        return [
            'defaults' => $state['defaults'],
            'draft' => $state['draft'],
            'published' => $state['published'],
            'effective' => $state['effective'],
            'meta' => [
                'version' => $state['version'],
                'has_draft_changes' => $state['has_draft_changes'],
                'published_at' => $state['published_at'],
                'draft_updated_at' => $state['draft_updated_at'],
            ],
            'urls' => [
                'saveDraft' => route('developer.appearance.save_draft'),
                'publish' => route('developer.appearance.publish'),
                'discard' => route('developer.appearance.discard'),
                'reset' => route('developer.appearance.reset'),
                'upload' => route('developer.appearance.upload'),
                'export' => route('developer.packages.export'),
                'import' => route('developer.packages.import'),
                'themeCss' => url('/branding/theme.css'),
            ],
            'allowedColorKeys' => AppearanceManager::ALLOWED_COLOR_KEYS,
            'fontKeys' => AppearanceManager::ALLOWED_FONT_KEYS,
            'assetKeys' => AppearanceManager::BRANDING_ASSET_KEYS,
        ];
    }

    /**
     * @return array<string, array{label: string, description: string, payload: array<string, mixed>}>
     */
    protected function presets(): array
    {
        return [
            'usm_green' => [
                'label' => 'USM Green',
                'description' => 'Green accent with gold primary highlights.',
                'payload' => [
                    'theme' => [
                        'brand-primary' => '#ffd700',
                        'brand-accent' => '#4caf50',
                        'brand-blue' => '#1f4ea7',
                        'brand-button-primary-bg' => '#4caf50',
                        'brand-button-primary-text' => '#ffffff',
                        'brand-sidebar-bg' => '#4caf50',
                        'brand-table-header-bg' => '#1f4ea7',
                        'brand-table-header-text' => '#ffffff',
                    ],
                    'buttons' => [
                        'brand-button-primary-bg' => '#4caf50',
                        'brand-button-primary-text' => '#ffffff',
                        'brand-button-primary-hover-bg' => '#2e7d32',
                        'brand-button-secondary-bg' => '#64748b',
                        'brand-button-secondary-text' => '#ffffff',
                        'brand-button-secondary-hover-bg' => '#475569',
                    ],
                ],
            ],
            'neutral' => [
                'label' => 'Neutral Slate',
                'description' => 'Muted slate palette for a calm staff UI.',
                'payload' => [
                    'theme' => [
                        'brand-primary' => '#0f172a',
                        'brand-accent' => '#334155',
                        'brand-blue' => '#1e293b',
                        'brand-button-primary-bg' => '#0f172a',
                        'brand-sidebar-bg' => '#1e293b',
                        'brand-table-header-bg' => '#334155',
                        'brand-table-header-text' => '#ffffff',
                    ],
                    'buttons' => [
                        'brand-button-primary-bg' => '#0f172a',
                        'brand-button-primary-text' => '#ffffff',
                        'brand-button-primary-hover-bg' => '#1e293b',
                        'brand-button-secondary-bg' => '#64748b',
                        'brand-button-secondary-text' => '#ffffff',
                        'brand-button-secondary-hover-bg' => '#475569',
                    ],
                ],
            ],
            'high_contrast' => [
                'label' => 'High Contrast',
                'description' => 'Black / white / yellow for maximum contrast.',
                'payload' => [
                    'theme' => [
                        'brand-primary' => '#ffff00',
                        'brand-accent' => '#000000',
                        'brand-blue' => '#0000ff',
                        'brand-text-dark' => '#000000',
                        'brand-text-light' => '#ffffff',
                        'brand-button-primary-bg' => '#000000',
                        'brand-button-primary-text' => '#ffff00',
                        'brand-sidebar-bg' => '#000000',
                        'brand-table-header-bg' => '#000000',
                        'brand-table-header-text' => '#ffff00',
                    ],
                    'buttons' => [
                        'brand-button-primary-bg' => '#000000',
                        'brand-button-primary-text' => '#ffff00',
                        'brand-button-primary-hover-bg' => '#222222',
                        'brand-button-secondary-bg' => '#ffff00',
                        'brand-button-secondary-text' => '#000000',
                        'brand-button-secondary-hover-bg' => '#e6e600',
                    ],
                ],
            ],
        ];
    }
}

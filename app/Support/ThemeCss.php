<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class ThemeCss
{
    /**
     * Generate allowlisted CSS custom properties from published branding tokens.
     */
    public static function render(): string
    {
        return Cache::remember(Branding::THEME_CACHE_KEY, now()->addHour(), function () {
            $tokens = Branding::tokens();
            $allowed = array_merge(
                \App\Services\AppearanceManager::ALLOWED_COLOR_KEYS,
                \App\Services\AppearanceManager::ALLOWED_FONT_KEYS,
            );

            $lines = [':root {'];
            foreach ($allowed as $key) {
                if (! array_key_exists($key, $tokens)) {
                    continue;
                }
                $value = (string) $tokens[$key];
                if (in_array($key, \App\Services\AppearanceManager::ALLOWED_FONT_KEYS, true)) {
                    $safe = preg_replace('/[^\w\s\',\-\.]/', '', $value) ?? '';
                    if ($safe === '') {
                        continue;
                    }
                    $lines[] = '  --'.$key.': '.$safe.';';
                    continue;
                }

                $normalized = (new \App\Services\AppearanceManager)->normalizeColor($value);
                if ($normalized === null) {
                    continue;
                }
                $lines[] = '  --'.$key.': '.$normalized.';';
            }
            $lines[] = '}';

            return implode("\n", $lines)."\n";
        });
    }

    public static function etag(): string
    {
        $resolved = Branding::resolved();

        return '"theme-v'.($resolved['version'] ?? 0).'-'.substr(sha1(self::render()), 0, 12).'"';
    }
}

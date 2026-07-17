<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use App\Services\AppearanceManager;
use App\Support\Branding;
use App\Support\ThemeCss;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperAppearanceTest extends TestCase
{
    use RefreshDatabase;

    protected function developer(): User
    {
        return User::factory()->create(['role' => 'developer']);
    }

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    public function test_guest_cannot_access_developer_dashboard(): void
    {
        $this->get('/developer')->assertRedirect('/login');
    }

    public function test_staff_cannot_access_developer_dashboard(): void
    {
        $this->actingAs($this->staff())
            ->get('/developer')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_developer_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/developer')
            ->assertForbidden();
    }

    public function test_developer_can_access_dashboard(): void
    {
        $this->actingAs($this->developer())
            ->get('/developer')
            ->assertOk();
    }

    public function test_draft_does_not_affect_published_tokens(): void
    {
        $user = $this->developer();
        $manager = app(AppearanceManager::class);

        $manager->saveDraft([
            'theme' => ['brand-primary' => '#112233'],
        ], $user);

        Branding::forgetCache();
        $this->assertNotEquals('#112233', Branding::tokens()['brand-primary'] ?? null);

        $manager->publish($user);
        Branding::forgetCache();
        $this->assertSame('#112233', Branding::tokens()['brand-primary'] ?? null);
    }

    public function test_theme_css_only_emits_allowlisted_tokens(): void
    {
        $user = $this->developer();
        $manager = app(AppearanceManager::class);
        $manager->saveDraft([
            'theme' => [
                'brand-primary' => '#abcdef',
                'evil-injection' => 'red; } body { display:none',
            ],
        ], $user);
        $manager->publish($user);
        Branding::forgetCache();

        $css = ThemeCss::render();
        $this->assertStringContainsString('--brand-primary: #abcdef;', $css);
        $this->assertStringNotContainsString('evil-injection', $css);
        $this->assertStringNotContainsString('display:none', $css);
    }

    public function test_theme_css_endpoint_is_public(): void
    {
        $this->get('/branding/theme.css')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=UTF-8');
    }

    public function test_reset_restores_config_fallbacks(): void
    {
        $user = $this->developer();
        $manager = app(AppearanceManager::class);
        $manager->saveDraft([
            'branding' => ['school_name' => 'Temp School'],
            'theme' => ['brand-primary' => '#010101'],
        ], $user);
        $manager->publish($user);
        $manager->resetToDefaults($user);

        Branding::forgetCache();
        $resolved = Branding::resolved();
        $this->assertSame(config('branding.school_name'), $resolved['school_name']);
        $this->assertSame(config('branding.tokens.brand-primary'), $resolved['tokens']['brand-primary']);
    }

    public function test_unknown_color_keys_are_rejected_from_draft(): void
    {
        $user = $this->developer();
        $manager = app(AppearanceManager::class);
        $setting = $manager->saveDraft([
            'theme' => [
                'brand-primary' => 'not-a-color',
                'brand-accent' => '#00ff00',
            ],
        ], $user);

        $theme = $setting->draft['theme'] ?? [];
        $this->assertArrayNotHasKey('brand-primary', $theme);
        $this->assertSame('#00ff00', $theme['brand-accent'] ?? null);
    }

    public function test_developer_login_redirects_to_console(): void
    {
        $user = $this->developer();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('developer.dashboard'));
    }
}

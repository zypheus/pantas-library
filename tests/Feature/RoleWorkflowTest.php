<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_home_page_loads_with_public_navigation_targets(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('landing'), false);
        $response->assertSee(route('feedback.create'), false);
        $response->assertSee(route('rooms.book'), false);
        $response->assertSee(route('login'), false);
        $response->assertSee(route('patron.register'), false);
        $response->assertSee('#about', false);
        $response->assertSee('#contact', false);
        $response->assertDontSee('href="#"', false);
    }

    public function test_index_path_redirects_to_home(): void
    {
        $this->get('/index')->assertRedirect(route('home'));
    }

    public function test_admin_login_reaches_catalog_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('book.index'));

        $this->actingAs($admin)
            ->get('/book')
            ->assertOk();
    }

    public function test_admin_login_ignores_developer_intended_url(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => bcrypt('password'),
        ]);

        $this->get('/developer/feature-flags')->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('book.index'));

        $this->actingAs($admin)
            ->get('/developer/feature-flags')
            ->assertForbidden();
    }

    public function test_admin_login_honors_allowed_staff_intended_url(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => bcrypt('password'),
        ]);

        $this->get('/students')->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect('/students');
    }

    public function test_developer_login_reaches_developer_console(): void
    {
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'role' => 'developer',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $developer->email,
            'password' => 'password',
        ])->assertRedirect(route('developer.dashboard'));

        $this->actingAs($developer)
            ->get('/developer')
            ->assertOk();
    }

    public function test_admin_cannot_access_developer_console(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/developer')
            ->assertForbidden();
    }

    public function test_developer_cannot_access_admin_catalog(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $this->actingAs($developer)
            ->get('/book')
            ->assertForbidden();
    }

    public function test_authenticated_admin_visiting_home_redirects_to_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/')
            ->assertRedirect(route('book.index'));
    }

    public function test_authenticated_developer_visiting_home_redirects_to_console(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $this->actingAs($developer)
            ->get('/')
            ->assertRedirect(route('developer.dashboard'));
    }

    public function test_opac_landing_page_has_working_navigation(): void
    {
        $response = $this->get('/opac');

        $response->assertOk();
        $response->assertSee(route('home'), false);
        $response->assertSee(route('kiosk.scan'), false);
        $response->assertSee(route('patron.register'), false);
        $response->assertSee(route('rooms.book'), false);
        $response->assertSee(route('feedback.create'), false);
        $response->assertSee(route('login'), false);
    }

    public function test_public_routes_are_reachable(): void
    {
        $this->get('/opac')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/feedback')->assertOk();
        $this->get('/rooms/book')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/kiosk/scan')->assertOk();
    }
}

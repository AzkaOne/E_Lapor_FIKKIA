<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_favicon_assets_exist(): void
    {
        $this->assertFileExists(public_path('assets/EL_logo.webp'));
        $this->assertFileExists(public_path('favicon.ico'));
        $this->assertGreaterThan(0, filesize(public_path('favicon.ico')));
        $this->assertFileExists(resource_path('views/komponen/favicon.blade.php'));
    }

    public function test_favicon_is_rendered_on_landing_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('assets/EL_logo.webp');
        $response->assertSee('rel="icon"', false);
    }

    public function test_favicon_is_rendered_on_auth_pages(): void
    {
        $loginRes = $this->get('/login');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('assets/EL_logo.webp');

        $registerRes = $this->get('/register');
        $registerRes->assertStatus(200);
        $registerRes->assertSee('assets/EL_logo.webp');
    }

    public function test_favicon_is_rendered_on_user_dashboards(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor']);
        $this->actingAs($pelapor)->get(route('pelapor.dashboard'))
            ->assertStatus(200)
            ->assertSee('assets/EL_logo.webp');

        $kabag = User::factory()->create(['role' => 'kabag']);
        $this->actingAs($kabag)->get(route('kabag.dashboard'))
            ->assertStatus(200)
            ->assertSee('assets/EL_logo.webp');
    }
}

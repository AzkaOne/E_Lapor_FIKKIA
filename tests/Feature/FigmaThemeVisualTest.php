<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;

class FigmaThemeVisualTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_figma_teal_palette(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('#0a8080', false);
        $response->assertSee('#ecfdf5', false);
    }

    public function test_authenticated_sidebar_renders_figma_dark_teal_sidebar(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get('/kabag/dashboard');
        $response->assertStatus(200);
        $response->assertSee('#0c3934', false);
        $response->assertSee('#1a5b51', false);
    }

    public function test_auth_pages_render_figma_teal_action_buttons(): void
    {
        $login = $this->get('/login');
        $login->assertStatus(200);
        $login->assertSee('#0a8080', false);

        $register = $this->get('/register');
        $register->assertStatus(200);
        $register->assertSee('#0a8080', false);
    }

    public function test_whistleblowing_and_tracking_render_teal_theme(): void
    {
        $wb = $this->get('/whistleblowing/buat');
        $wb->assertStatus(200);
        $wb->assertSee('#0a8080', false);

        $track = $this->get('/tracking');
        $track->assertStatus(200);
        $track->assertSee('#0a8080', false);
    }

    public function test_pelapor_portal_renders_teal_theme(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        
        $dashboard = $this->actingAs($pelapor)->get('/pelapor/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('#0a8080', false);

        $status = $this->actingAs($pelapor)->get('/pelapor/status');
        $status->assertStatus(200);
        $status->assertSee('#0a8080', false);

        $history = $this->actingAs($pelapor)->get('/pelapor/riwayat');
        $history->assertStatus(200);
        $history->assertSee('#0a8080', false);
    }

    public function test_kabag_suite_renders_teal_theme(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag', 'email' => 'kabag@unair.ac.id']);
        
        $dashboard = $this->actingAs($kabag)->get('/kabag/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('#0a8080', false);
        $dashboard->assertSee('#0c3934', false);

        $wb = $this->actingAs($kabag)->get('/kabag/whistleblowing');
        $wb->assertStatus(200);
        $wb->assertSee('#0a8080', false);
        $wb->assertSee('#0c3934', false);

        $verif = $this->actingAs($kabag)->get('/kabag/verifikasi');
        $verif->assertStatus(200);
        $verif->assertSee('#0a8080', false);
    }
}
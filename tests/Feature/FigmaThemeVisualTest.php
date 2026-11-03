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
        $content = strtolower($response->getContent());
        $this->assertTrue(str_contains($content, '#0a8080'));
        $this->assertTrue(str_contains($content, '#ecfdf5'));
    }

    public function test_authenticated_sidebar_renders_figma_dark_teal_sidebar(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get('/kabag/dashboard');
        $response->assertStatus(200);
        $content = strtolower($response->getContent());
        $this->assertTrue(str_contains($content, '#0c3934'));
        $this->assertTrue(str_contains($content, '#1a5b51'));
    }

    public function test_auth_pages_render_figma_teal_action_buttons(): void
    {
        $login = $this->get('/login');
        $login->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($login->getContent()), '#0a8080') || str_contains(strtolower($login->getContent()), '#0a7075'));

        $register = $this->get('/register');
        $register->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($register->getContent()), '#0a8080') || str_contains(strtolower($register->getContent()), '#0a7075'));
    }

    public function test_whistleblowing_and_tracking_render_teal_theme(): void
    {
        $wb = $this->get('/whistleblowing/buat');
        $wb->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($wb->getContent()), '#0a8080'));

        $track = $this->get('/tracking');
        $track->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($track->getContent()), '#0a8080'));
    }

    public function test_pelapor_portal_renders_teal_theme(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        
        $dashboard = $this->actingAs($pelapor)->get('/pelapor/dashboard');
        $dashboard->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($dashboard->getContent()), '#0a8080'));

        $status = $this->actingAs($pelapor)->get('/pelapor/status');
        $status->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($status->getContent()), '#0a8080'));

        $history = $this->actingAs($pelapor)->get('/pelapor/riwayat');
        $history->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($history->getContent()), '#0a8080'));
    }

    public function test_kabag_suite_renders_teal_theme(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag', 'email' => 'kabag@unair.ac.id']);
        
        $dashboard = $this->actingAs($kabag)->get('/kabag/dashboard');
        $dashboard->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($dashboard->getContent()), '#0a8080'));
        $this->assertTrue(str_contains(strtolower($dashboard->getContent()), '#0c3934'));

        $wb = $this->actingAs($kabag)->get('/kabag/whistleblowing');
        $wb->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($wb->getContent()), '#0a8080'));
        $this->assertTrue(str_contains(strtolower($wb->getContent()), '#0c3934'));

        $verif = $this->actingAs($kabag)->get('/kabag/verifikasi');
        $verif->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($verif->getContent()), '#0a8080'));
    }

    public function test_kasubag_and_petugas_render_teal_theme(): void
    {
        $dept = \App\Models\SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $dept->id, 'email' => 'ksb@unair.ac.id']);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $dept->id, 'email' => 'ptg@unair.ac.id']);

        $ksbDash = $this->actingAs($kasubag)->get('/kasubag/dashboard');
        $ksbDash->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($ksbDash->getContent()), '#0c3934'));
        $this->assertTrue(str_contains(strtolower($ksbDash->getContent()), '#0a8080'));

        $ptgDash = $this->actingAs($petugas)->get('/petugas/dashboard');
        $ptgDash->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($ptgDash->getContent()), '#0c3934'));
        $this->assertTrue(str_contains(strtolower($ptgDash->getContent()), '#0a8080'));
    }
}
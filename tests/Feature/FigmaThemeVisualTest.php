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
}
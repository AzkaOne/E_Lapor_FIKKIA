<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagUserImportBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_blade_renders_import_button_and_template_link(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)->get(route('kabag.manajemen-pegawai'));

        $response->assertStatus(200);
        $response->assertSee(route('kabag.users.import'));
        $response->assertSee(route('kabag.users.template'));
        $response->assertSee('Import');
    }

    public function test_blade_renders_import_result_popup_when_session_present(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)
            ->withSession([
                'import_result' => [
                    'saved_count' => 5,
                    'failed_count' => 2,
                    'failed_rows' => [
                        ['row' => 3, 'name' => 'Budi', 'reason' => 'Role tidak sesuai'],
                    ],
                ]
            ])
            ->get(route('kabag.manajemen-pegawai'));

        $response->assertStatus(200);
        $response->assertSee('5 data pegawai tersimpan');
        $response->assertSee('2 data pegawai tidak tersimpan');
    }
}

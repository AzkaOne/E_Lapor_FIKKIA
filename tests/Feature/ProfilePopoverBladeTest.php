<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePopoverBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_pelapor_renders_profile_popover_and_pelapor_fields(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'pelapor',
            'identity_number' => '1234567890',
            'position' => 'Mahasiswa',
            'avatar' => 'profil_men.webp',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('assets/profil_men.webp');
        $response->assertSee('1234567890');
        $response->assertSee('Mahasiswa');
        $response->assertSee('profil_popover');
        $response->assertSee('centerAvatarToggleBtn');
        $response->assertSee('value="mahasiswa"', false);
        $response->assertSee('value="tendik"', false);
        $response->assertSee('value="dosen"', false);
        $response->assertDontSee('Bidang Pegawai');
    }

    public function test_header_kabag_renders_profile_popover_with_staff_badges(): void
    {
        $subDept = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kabag = User::factory()->create([
            'role' => 'kabag',
            'identity_number' => '198501012010121001',
            'position' => 'Kepala Bagian',
            'sub_department_id' => $subDept->id,
            'avatar' => 'profil_men.webp',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('assets/profil_men.webp');
        $response->assertSee('198501012010121001');
        $response->assertSee('Kepala Bagian');
        $response->assertSee('Sarana dan Prasarana');
        $response->assertSee('Aktif');
        $response->assertSee('profil_popover');
    }
}

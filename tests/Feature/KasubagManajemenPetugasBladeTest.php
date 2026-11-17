<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class KasubagManajemenPetugasBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_manajemen_petugas_blade_renders_correctly_without_boxes(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create([
            'name' => 'Kasubag Sarpras',
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);
        $petugas = User::factory()->create([
            'name' => 'Petugas Lapangan A',
            'role' => 'petugas',
            'sub_department_id' => $sarpras->id,
        ]);

        $response = $this->actingAs($kasubag)->get(route('kasubag.petugas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kasubag.manajemen_petugas');

        // Check header and title
        $response->assertSee('Manajemen Petugas');
        $response->assertSee('Tambah Petugas Baru');

        // Check that stat boxes are NOT present
        $response->assertDontSee('Total Pegawai</div>', false);
        $response->assertDontSee('Akademik</div>', false);
        $response->assertDontSee('Keuangan &amp; SDM</div>', false);

        // Check both users are in table
        $response->assertSee('Kasubag Sarpras');
        $response->assertSee('Petugas Lapangan A');

        // Check detail button exists
        $response->assertSee('Detail');

        // Check import modal components exist
        $response->assertSee('Import Excel / CSV');
        $response->assertSee('Unduh Template Petugas');
        $response->assertSee(route('kasubag.petugas.template'));
        $response->assertSee(route('kasubag.petugas.import'));
    }

    public function test_kasubag_can_import_petugas_using_excel_csv(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        $csvContent = "\xEF\xBB\xBF" .
            "Nama,Email,NIP\n" .
            "Petugas Import 1,import1@unair.ac.id,199001012020011001\n" .
            "Petugas Import 2,import2@unair.ac.id,199001012020011002\n";

        $file = UploadedFile::fake()->createWithContent('petugas.csv', $csvContent);

        $response = $this->actingAs($kasubag)->post(route('kasubag.petugas.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('kasubag.petugas.index'));
        $response->assertSessionHas('import_result');

        $result = session('import_result');
        $this->assertEquals(2, $result['saved_count']);
        $this->assertEquals(0, $result['failed_count']);

        $user1 = User::where('email', 'import1@unair.ac.id')->first();
        $this->assertNotNull($user1);
        $this->assertEquals('petugas', $user1->role);
        $this->assertEquals($sarpras->id, $user1->sub_department_id);
    }

    public function test_kasubag_can_download_petugas_csv_template(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        $response = $this->actingAs($kasubag)->get(route('kasubag.petugas.template'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('template_import_petugas.csv', $response->headers->get('Content-Disposition'));
    }
}

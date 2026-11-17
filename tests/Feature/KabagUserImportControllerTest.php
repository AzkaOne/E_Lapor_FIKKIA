<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class KabagUserImportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_import_pegawai_csv_and_receives_flash_result(): void
    {
        SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $csvContent = "Nama,Email,NIP,Role,Bidang\n" .
                      "Doni Pratama,doni@unair.ac.id,19800101,petugas,Sarana dan Prasarana\n" .
                      "Invalid Guy,invalid@unair.ac.id,19800102,direktur,Sarana dan Prasarana\n";

        $file = UploadedFile::fake()->createWithContent('pegawai.csv', $csvContent);

        $response = $this->actingAs($kabag)->post(route('kabag.users.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('import_result');
        $sessionResult = session('import_result');
        $this->assertEquals(1, $sessionResult['saved_count']);
        $this->assertEquals(1, $sessionResult['failed_count']);
    }

    public function test_kabag_can_download_template(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)->get(route('kabag.users.template'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }
}

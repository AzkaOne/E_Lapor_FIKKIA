<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagRiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_view_riwayat_page_with_matching_layout_and_only_verified_finished_reports(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $akademik = SubDepartment::create(['name' => 'Pendidikan & Akademik', 'code' => 'AKD']);

        // 1. Report finished verified (terlaksana)
        Report::create([
            'ticket_code' => 'LP-SELESAI-TL',
            'title' => 'Perbaikan Lampu Taman Selesai',
            'description' => 'Lampu taman telah diganti',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
            'rating' => 5,
        ]);

        // 2. Report finished verified (tertunda)
        Report::create([
            'ticket_code' => 'LP-SELESAI-TT',
            'title' => 'Pengadaan Meja Sidang Selesai Ditunda',
            'description' => 'Anggaran telah dialihkan',
            'type' => 'pelayanan',
            'sub_department_id' => $akademik->id,
            'campus_location' => 'mojo',
            'status' => 'selesai',
            'execution_result' => 'tertunda',
        ]);

        // 3. Report still in verification queue
        Report::create([
            'ticket_code' => 'LP-QUEUE-VERIF',
            'title' => 'Laporan Masih Menunggu Verifikasi',
            'description' => 'Belum disahkan kabag',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        // 4. Report still in progress
        Report::create([
            'ticket_code' => 'LP-PROSES-1',
            'title' => 'Laporan Masih Dikerjakan',
            'description' => 'Dalam proses',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'proses',
        ]);

        // 5. Rejected report
        Report::create([
            'ticket_code' => 'LP-DITOLAK-1',
            'title' => 'Laporan Ditolak Sistem',
            'description' => 'Ditolak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'ditolak',
        ]);

        $response = $this->actingAs($kabag)->get('/kabag/riwayat');
        $response->assertStatus(200);
        $response->assertViewIs('kabag.riwayat_kabag');

        // Should see completed reports
        $response->assertSee('Perbaikan Lampu Taman Selesai');
        $response->assertSee('Pengadaan Meja Sidang Selesai Ditunda');
        $response->assertSee('Terlaksana');
        $response->assertSee('Tertunda');
        $response->assertSee('Lihat Detail');

        // Verify updated table structure and removals
        $response->assertSee('Tanggal Laporan');
        $response->assertSee('Tanggal Selesai');
        $response->assertSee('Status');
        $response->assertSee('ID Laporan: LP-SELESAI-TL');
        $response->assertDontSee('Tiket Selesai');
        $response->assertDontSee('Kampus Sobo');
        $response->assertDontSee('Kampus Mojo');
        $response->assertDontSee('Pelaksana:');

        // Should NOT see uncompleted or rejected reports
        $response->assertDontSee('Laporan Masih Menunggu Verifikasi');
        $response->assertDontSee('Laporan Masih Dikerjakan');
        $response->assertDontSee('Laporan Ditolak Sistem');

        // Test search filter
        $resSearch = $this->actingAs($kabag)->get('/kabag/riwayat?search=Lampu');
        $resSearch->assertSee('Perbaikan Lampu Taman Selesai');
        $resSearch->assertDontSee('Pengadaan Meja Sidang Selesai Ditunda');

        // Test status terlaksana filter
        $resFilterTl = $this->actingAs($kabag)->get('/kabag/riwayat?status=terlaksana');
        $resFilterTl->assertSee('Perbaikan Lampu Taman Selesai');
        $resFilterTl->assertDontSee('Pengadaan Meja Sidang Selesai Ditunda');

        // Test status tertunda filter
        $resFilterTt = $this->actingAs($kabag)->get('/kabag/riwayat?status=tertunda');
        $resFilterTt->assertSee('Pengadaan Meja Sidang Selesai Ditunda');
        $resFilterTt->assertDontSee('Perbaikan Lampu Taman Selesai');
    }
}

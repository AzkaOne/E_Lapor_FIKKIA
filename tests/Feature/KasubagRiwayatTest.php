<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasubagRiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_can_view_riwayat_page_with_matching_layout_and_only_verified_finished_reports(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $akademik = SubDepartment::create(['name' => 'Pendidikan & Akademik', 'code' => 'AKD']);

        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
            'email' => 'ksb.sarpras@unair.ac.id',
        ]);

        // 1. Report finished & verified (terlaksana) for Sarpras
        Report::create([
            'ticket_code' => 'LP-KSB-SELESAI-TL',
            'title' => 'Perbaikan Lampu Lab Gigi Selesai',
            'description' => 'Lampu telah diganti baru',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
            'rating' => 5,
        ]);

        // 2. Report finished & verified (tertunda) for Sarpras
        Report::create([
            'ticket_code' => 'LP-KSB-SELESAI-TT',
            'title' => 'Penggantian Pintu Ruang 101 Ditunda',
            'description' => 'Menunggu material dari Surabaya',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'selesai',
            'execution_result' => 'tertunda',
        ]);

        // 3. Report waiting verification (verifikasi_kabag) for Sarpras - should NOT appear in riwayat
        Report::create([
            'ticket_code' => 'LP-KSB-VERIF',
            'title' => 'Laporan Menunggu Verifikasi Kabag',
            'description' => 'Pekerjaan selesai tapi belum diverifikasi kabag',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        // 4. Report in progress for Sarpras - should NOT appear in riwayat
        Report::create([
            'ticket_code' => 'LP-KSB-PROSES',
            'title' => 'Laporan Sedang Dikerjakan Petugas',
            'description' => 'Dalam proses teknisi',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'proses',
        ]);

        // 5. Rejected report for Sarpras - should NOT appear in verified riwayat
        Report::create([
            'ticket_code' => 'LP-KSB-TOLAK',
            'title' => 'Laporan Ditolak',
            'description' => 'Ditolak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'ditolak',
        ]);

        // 6. Finished report from ANOTHER sub-department (Akademik) - should NOT appear for Sarpras Kasubag
        Report::create([
            'ticket_code' => 'LP-OTHER-SELESAI',
            'title' => 'Laporan Akademik Selesai',
            'description' => 'Unit lain',
            'type' => 'pelayanan',
            'sub_department_id' => $akademik->id,
            'campus_location' => 'sobo',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($kasubag)->get('/kasubag/riwayat');
        $response->assertStatus(200);
        $response->assertViewIs('kasubag.riwayat_kasubag');

        // Should see completed reports belonging to this Kasubag's unit
        $response->assertSee('Perbaikan Lampu Lab Gigi Selesai');
        $response->assertSee('Penggantian Pintu Ruang 101 Ditunda');
        $response->assertSee('LP-KSB-SELESAI-TL');
        $response->assertSee('LP-KSB-SELESAI-TT');
        $response->assertSee('Terlaksana');
        $response->assertSee('Tertunda');
        $response->assertSee('Lihat Detail');

        // Verify table structure
        $response->assertSee('Riwayat Laporan Selesai');
        $response->assertSee('Tanggal Laporan');
        $response->assertSee('Tanggal Selesai');
        $response->assertSee('Total Selesai');

        // Should NOT see uncompleted, rejected, or other unit reports
        $response->assertDontSee('Laporan Menunggu Verifikasi Kabag');
        $response->assertDontSee('Laporan Sedang Dikerjakan Petugas');
        $response->assertDontSee('Laporan Ditolak');
        $response->assertDontSee('Laporan Akademik Selesai');
    }

    public function test_kasubag_can_filter_riwayat_by_search_and_status(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);

        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        Report::create([
            'ticket_code' => 'LP-AC-SELESAI',
            'title' => 'Perbaikan AC Rusak Selesai',
            'description' => 'AC selesai',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        Report::create([
            'ticket_code' => 'LP-KURSI-SELESAI',
            'title' => 'Pengadaan Kursi Ditunda',
            'description' => 'Kursi ditunda',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'selesai',
            'execution_result' => 'tertunda',
        ]);

        // Filter search
        $resSearch = $this->actingAs($kasubag)->get('/kasubag/riwayat?search=AC');
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Perbaikan AC Rusak Selesai');
        $resSearch->assertDontSee('Pengadaan Kursi Ditunda');

        // Filter status terlaksana
        $resTl = $this->actingAs($kasubag)->get('/kasubag/riwayat?status=terlaksana');
        $resTl->assertStatus(200);
        $resTl->assertSee('Perbaikan AC Rusak Selesai');
        $resTl->assertDontSee('Pengadaan Kursi Ditunda');

        // Filter status tertunda
        $resTt = $this->actingAs($kasubag)->get('/kasubag/riwayat?status=tertunda');
        $resTt->assertStatus(200);
        $resTt->assertSee('Pengadaan Kursi Ditunda');
        $resTt->assertDontSee('Perbaikan AC Rusak Selesai');
    }
}

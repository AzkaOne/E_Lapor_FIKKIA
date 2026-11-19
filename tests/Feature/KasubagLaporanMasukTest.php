<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasubagLaporanMasukTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_can_view_laporan_masuk_page_with_six_boxes(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
            'email' => 'ksb.sarpras@unair.ac.id',
        ]);

        // Create sample reports for this unit
        Report::create([
            'ticket_code' => 'LP-TEST-1',
            'title' => 'Kerusakan Lampu Kelas 1',
            'description' => 'Lampu mati di ruang kelas',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Sobo',
            'status' => 'didisposisi',
        ]);

        Report::create([
            'ticket_code' => 'LP-TEST-2',
            'title' => 'Perbaikan Pintu Utama',
            'description' => 'Pintu engsel rusak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Mojo',
            'status' => 'proses',
        ]);

        Report::create([
            'ticket_code' => 'LP-TEST-3',
            'title' => 'AC Ruang Kuliah Rusak',
            'description' => 'AC bocor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Giri',
            'status' => 'selesai',
        ]);

        // Check /kasubag/status
        $response = $this->actingAs($kasubag)->get('/kasubag/status');
        $response->assertStatus(200);
        $response->assertSee('Total Laporan');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Laporan Terlaksana');
        $response->assertSee('Laporan Tertunda');
        $response->assertSee('Laporan Proses');
        $response->assertSee('Laporan Revisi');
        $response->assertSee('LP-TEST-1');
        $response->assertSee('LP-TEST-2');
        $response->assertDontSee('LP-TEST-3');

        // Check riwayat has the completed report
        $responseHistory = $this->actingAs($kasubag)->get('/kasubag/riwayat');
        $responseHistory->assertStatus(200);
        $responseHistory->assertSee('LP-TEST-3');

        // Check alias /kasubag/laporan-masuk
        $responseAlias = $this->actingAs($kasubag)->get('/kasubag/laporan-masuk');
        $responseAlias->assertStatus(200);
        $responseAlias->assertSee('Total Laporan');
        $responseAlias->assertDontSee('LP-TEST-3');
    }

    public function test_kasubag_laporan_masuk_filtering_by_status(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        Report::create([
            'ticket_code' => 'LP-INCOMING-1',
            'title' => 'Laporan Masuk Baru',
            'description' => 'Laporan Baru',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Sobo',
            'status' => 'didisposisi',
        ]);

        Report::create([
            'ticket_code' => 'LP-COMPLETED-1',
            'title' => 'Laporan Sudah Selesai',
            'description' => 'Laporan Selesai',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Mojo',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($kasubag)->get('/kasubag/status?status=masuk');
        $response->assertStatus(200);
        $response->assertSee('LP-INCOMING-1');
        $response->assertDontSee('LP-COMPLETED-1');
    }

    public function test_kasubag_cannot_see_or_access_diajukan_reports_until_disposed_by_kabag(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);
        $pelapor = User::factory()->create(['role' => 'pelapor']);

        // Report newly sent by pelapor (status = diajukan)
        $reportBaru = Report::create([
            'ticket_code' => 'LP-BELUM-DISPOSISI',
            'title' => 'Laporan Belum Didisposisi Kabag',
            'description' => 'Masih menunggu disposisi kabag',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Mojo',
            'status' => 'diajukan',
            'user_id' => $pelapor->id,
        ]);

        // 1. Must NOT appear on Kasubag Dashboard
        $dashboardRes = $this->actingAs($kasubag)->get(route('kasubag.dashboard'));
        $dashboardRes->assertStatus(200);
        $dashboardRes->assertDontSee('LP-BELUM-DISPOSISI');
        $dashboardRes->assertDontSee('Laporan Belum Didisposisi Kabag');

        // 2. Must NOT appear on Kasubag Laporan Masuk / Status
        $statusRes = $this->actingAs($kasubag)->get(route('kasubag.status'));
        $statusRes->assertStatus(200);
        $statusRes->assertDontSee('LP-BELUM-DISPOSISI');
        $statusRes->assertDontSee('Laporan Belum Didisposisi Kabag');

        $statusFilterRes = $this->actingAs($kasubag)->get(route('kasubag.status', ['status' => 'masuk']));
        $statusFilterRes->assertStatus(200);
        $statusFilterRes->assertDontSee('LP-BELUM-DISPOSISI');

        // 3. Kasubag CANNOT open direct detail URL for diajukan report (403 Forbidden)
        $showRes = $this->actingAs($kasubag)->get(route('kasubag.show', $reportBaru));
        $showRes->assertStatus(403);

        // 4. Kasubag CANNOT self-execute, dispose, or reject diajukan report (403 Forbidden)
        $selfExecRes = $this->actingAs($kasubag)->post(route('kasubag.self-execute', $reportBaru));
        $selfExecRes->assertStatus(403);

        // 5. When Kabag disposes it to Kasubag (status becomes didisposisi), now Kasubag CAN see and access it
        $reportBaru->update(['status' => 'didisposisi']);

        $dashboardAfterRes = $this->actingAs($kasubag)->get(route('kasubag.dashboard'));
        $dashboardAfterRes->assertStatus(200);
        $dashboardAfterRes->assertSee('LP-BELUM-DISPOSISI');

        $statusAfterRes = $this->actingAs($kasubag)->get(route('kasubag.status'));
        $statusAfterRes->assertStatus(200);
        $statusAfterRes->assertSee('LP-BELUM-DISPOSISI');

        $showAfterRes = $this->actingAs($kasubag)->get(route('kasubag.show', $reportBaru));
        $showAfterRes->assertStatus(200);
        $showAfterRes->assertSee('LP-BELUM-DISPOSISI');
    }

    public function test_whistleblowing_reports_are_only_accessible_by_kabag_and_completely_hidden_from_kasubag_and_petugas(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $sarpras->id,
        ]);

        $wbReport = Report::create([
            'ticket_code' => 'WB-RAHASIA-001',
            'title' => 'Laporan Rahasia Whistleblowing',
            'description' => 'Dugaan pelanggaran rahasia',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Sobo',
            'status' => 'diajukan',
        ]);

        // Kabag CAN see and access it in whistleblowing section
        $kabagIndex = $this->actingAs($kabag)->get(route('kabag.whistleblowing'));
        $kabagIndex->assertStatus(200);
        $kabagIndex->assertSee('WB-RAHASIA-001');

        $kabagShow = $this->actingAs($kabag)->get(route('kabag.show', $wbReport));
        $kabagShow->assertStatus(200);
        $kabagShow->assertSee('WB-RAHASIA-001');

        // Kasubag CANNOT see it on dashboard, status/laporan-masuk, or riwayat
        $kasubagDash = $this->actingAs($kasubag)->get(route('kasubag.dashboard'));
        $kasubagDash->assertStatus(200);
        $kasubagDash->assertDontSee('WB-RAHASIA-001');

        $kasubagStatus = $this->actingAs($kasubag)->get(route('kasubag.status'));
        $kasubagStatus->assertStatus(200);
        $kasubagStatus->assertDontSee('WB-RAHASIA-001');

        $kasubagHistory = $this->actingAs($kasubag)->get(route('kasubag.history'));
        $kasubagHistory->assertStatus(200);
        $kasubagHistory->assertDontSee('WB-RAHASIA-001');

        // Kasubag gets 403 Forbidden when trying to access show
        $kasubagShow = $this->actingAs($kasubag)->get(route('kasubag.show', $wbReport));
        $kasubagShow->assertStatus(403);

        // Petugas also gets 403 Forbidden
        $petugasShow = $this->actingAs($petugas)->get(route('petugas.show', $wbReport));
        $petugasShow->assertStatus(403);
    }
}

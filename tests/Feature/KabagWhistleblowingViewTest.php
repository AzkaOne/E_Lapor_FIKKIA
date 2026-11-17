<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagWhistleblowingViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_view_whistleblowing_page_with_four_boxes_and_table(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);

        // Create sample whistleblowing reports with different statuses
        $wb1 = Report::create([
            'ticket_code' => 'WB-TEST-001',
            'title' => 'Dugaan Pungli Parkir',
            'description' => 'Ada oknum meminta pungutan liar di area parkir',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        Report::create([
            'ticket_code' => 'WB-TEST-002',
            'title' => 'Indikasi Pelanggaran SOP Akademik',
            'description' => 'Ujian tidak sesuai standar operasional',
            'type' => 'whistleblowing',
            'sub_department_id' => $dekanat->id,
            'campus_location' => 'mojo',
            'status' => 'proses',
        ]);

        Report::create([
            'ticket_code' => 'WB-TEST-003',
            'title' => 'Penyalahgunaan Fasilitas Laboratorium',
            'description' => 'Alat lab dibawa keluar tanpa izin resmi',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'selesai',
            'resolution_notes' => 'Telah dilakukan teguran dan barang dikembalikan ke lab.',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.whistleblowing'));

        $response->assertStatus(200);

        // Tabs matching laporan_pelayanan
        $response->assertSee('Laporan Whistleblowing');
        $response->assertSee('Laporan Saya');

        // Four stat boxes on default tab
        $response->assertSee('Semua Laporan');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Proses');
        $response->assertSee('Selesai');

        // Check values in counts and table:
        // WB-TEST-001 (diajukan) is shown in Laporan Whistleblowing tab
        $response->assertSee('WB-TEST-001');
        // WB-TEST-002 (proses) moved to Laporan Saya, so it must NOT appear here
        $response->assertDontSee('WB-TEST-002');
        // WB-TEST-003 (selesai) moved to Riwayat, so it must NOT appear here
        $response->assertDontSee('WB-TEST-003');

        // Check Laporan Saya tab shows WB-TEST-002
        $responseSaya = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $responseSaya->assertSee('WB-TEST-002');
        $responseSaya->assertDontSee('WB-TEST-001');
        $responseSaya->assertDontSee('WB-TEST-003');

        // Check Riwayat shows WB-TEST-003
        $responseHistory = $this->actingAs($kabag)->get(route('kabag.history'));
        $responseHistory->assertSee('WB-TEST-003');

        // Table headers matching laporan_pelayanan
        $response->assertSee('Judul Laporan');
        $response->assertSee('Pelapor');
        $response->assertSee('Bidang');
        $response->assertSee('Tanggal Laporan');
        $response->assertSee('Status');
        $response->assertSee('Aksi');

        // Pelapor is anonymous
        $response->assertSee('Anonim');
        $response->assertSee('Whistleblower');

        // Detail link exists to detail_Kabag
        $response->assertSee('Lihat Detail');
        $response->assertSee(route('kabag.show', $wb1));
    }

    public function test_kabag_can_filter_whistleblowing_by_status_and_search(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        Report::create([
            'ticket_code' => 'WB-FILTER-01',
            'title' => 'Laporan Masuk Khusus',
            'description' => 'Deskripsi laporan baru masuk',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        Report::create([
            'ticket_code' => 'WB-FILTER-02',
            'title' => 'Pengaduan Parkir Liar Khusus',
            'description' => 'Ada pungli parkir di kampus',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        Report::create([
            'ticket_code' => 'WB-FILTER-03',
            'title' => 'Laporan Sudah Selesai',
            'description' => 'Deskripsi laporan yang telah beres',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'selesai',
            'resolution_notes' => 'Investigasi tuntas.',
        ]);

        // Filter by status=diajukan
        $response = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['status' => 'diajukan']));
        $response->assertStatus(200);
        $response->assertSee('WB-FILTER-01');
        $response->assertSee('WB-FILTER-02');
        $response->assertDontSee('WB-FILTER-03');

        // Filter by search in Laporan Whistleblowing tab
        $responseSearch = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['search' => 'Parkir Liar Khusus']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('WB-FILTER-02');
        $responseSearch->assertDontSee('WB-FILTER-01');

        // Completed report does NOT appear in Laporan Whistleblowing tab search
        $responseCompletedSearch = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['search' => 'Sudah Selesai']));
        $responseCompletedSearch->assertStatus(200);
        $responseCompletedSearch->assertDontSee('WB-FILTER-03');
    }

    public function test_kabag_laporan_saya_tab_only_shows_whistleblowing_reports_being_worked_on(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        $diajukan = Report::create([
            'ticket_code' => 'WB-SAYA-01',
            'title' => 'Pengaduan Masuk Baru',
            'description' => 'Belum dikerjakan',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        $proses = Report::create([
            'ticket_code' => 'WB-SAYA-02',
            'title' => 'Pengaduan Sedang Dikerjakan Kabag',
            'description' => 'Sedang diproses investigasi',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'proses',
            'handler_id' => $kabag->id,
        ]);

        $selesai = Report::create([
            'ticket_code' => 'WB-SAYA-03',
            'title' => 'Pengaduan Tuntas Selesai',
            'description' => 'Sudah selesai',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'selesai',
            'handler_id' => $kabag->id,
            'resolution_notes' => 'Tuntas.',
        ]);

        // Access Laporan Saya tab
        $response = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $response->assertStatus(200);

        // Should see the tab navigation
        $response->assertSee('Laporan Whistleblowing');
        $response->assertSee('Laporan Saya');

        // Only the report in 'proses' (dikerjakan) should appear
        $response->assertSee('WB-SAYA-02');
        $response->assertSee('Pengaduan Sedang Dikerjakan Kabag');

        // Other statuses should NOT appear in Laporan Saya
        $response->assertDontSee('WB-SAYA-01');
        $response->assertDontSee('WB-SAYA-03');

        // Now process $diajukan via kabag.whistleblowing.process
        $processResponse = $this->actingAs($kabag)->post(route('kabag.whistleblowing.process', $diajukan));
        $processResponse->assertStatus(302);

        $diajukan->refresh();
        $this->assertEquals('proses', $diajukan->status);
        $this->assertEquals($kabag->id, $diajukan->handler_id);

        // Now it should appear in tab=saya
        $responseAfter = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $responseAfter->assertSee('WB-SAYA-01');
    }

    public function test_kabag_whistleblowing_detail_renders_only_two_menus_and_anonymous_pelapor_data(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'WB-DETAIL-01',
            'title' => 'Pengaduan Whistleblowing Uji Coba',
            'description' => 'Isi uraian whistleblowing',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.show', $report));
        $response->assertStatus(200);

        // Header and Badge
        $response->assertSee('Whistleblowing');
        $response->assertSee('WB-DETAIL-01');

        // Pelapor data should be replaced with '------'
        $response->assertSee('------');

        // Only 2 action menus: Kerjakan Mandiri and Tolak Laporan
        $response->assertSee('Kerjakan Mandiri');
        $response->assertSee('Tolak Laporan');

        // Should NOT see Disposisi Ke Kasubag or Kirim ke Petugas
        $response->assertDontSee('Disposisi Ke Kasubag');
        $response->assertDontSee('Kirim ke Petugas');
    }

    public function test_kabag_whistleblowing_self_execution_box_and_completion_to_riwayat(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'WB-SELF-01',
            'title' => 'Whistleblowing Kerjakan Mandiri',
            'description' => 'Pengaduan yang ditangani mandiri',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        // Kabag self executes
        $selfRes = $this->actingAs($kabag)->post(route('kabag.self-execute', $report));
        $selfRes->assertStatus(302);

        $report->refresh();
        $this->assertEquals('proses', $report->status);
        $this->assertEquals($kabag->id, $report->handler_id);

        // View detail in self executing state
        $detailRes = $this->actingAs($kabag)->get(route('kabag.show', $report));
        $detailRes->assertStatus(200);

        // Form description & file input should exist
        $detailRes->assertSee('Deskripsi Hasil Pengerjaan / Tindak Lanjut');
        $detailRes->assertSee('Bukti Laporan');
        $detailRes->assertSee('Selesaikan Laporan');

        // Should NOT see 2 buttons (Laporan Tertunda & Laporan Terlaksana)
        $detailRes->assertDontSee('Laporan Tertunda');
        $detailRes->assertDontSee('Laporan Terlaksana');

        // Now complete the self execution
        $completeRes = $this->actingAs($kabag)->post(route('kabag.complete-self', $report), [
            'notes' => 'Investigasi pimpinan telah selesai dan dituntaskan.',
        ]);

        // Must redirect to kabag.history
        $completeRes->assertRedirect(route('kabag.history'));

        $report->refresh();
        $this->assertEquals('selesai', $report->status);
        $this->assertEquals('Investigasi pimpinan telah selesai dan dituntaskan.', $report->resolution_notes);

        // Check kabag.history shows this whistleblowing report
        $historyRes = $this->actingAs($kabag)->get(route('kabag.history'));
        $historyRes->assertStatus(200);
        $historyRes->assertSee('WB-SELF-01');
        $historyRes->assertSee('Whistleblowing Kerjakan Mandiri');

        // Check filter options include Pelayanan and Whistleblowing
        $historyRes->assertSee('Pelayanan');
        $historyRes->assertSee('Whistleblowing');

        // Filter by status=whistleblowing
        $filterWbRes = $this->actingAs($kabag)->get(route('kabag.history', ['status' => 'whistleblowing']));
        $filterWbRes->assertStatus(200);
        $filterWbRes->assertSee('WB-SELF-01');

        // Filter by status=pelayanan should not show it
        $filterPelayananRes = $this->actingAs($kabag)->get(route('kabag.history', ['status' => 'pelayanan']));
        $filterPelayananRes->assertStatus(200);
        $filterPelayananRes->assertDontSee('WB-SELF-01');
    }

    public function test_whistleblowing_lifecycle_from_masuk_to_laporan_saya_then_riwayat(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'WB-FLOW-001',
            'title' => 'Dugaan Pelanggaran Kode Etik',
            'description' => 'Ada bukti pelanggaran kode etik',
            'type' => 'whistleblowing',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        // 1. Initial State: Appears in Laporan Whistleblowing tab, NOT in Laporan Saya, NOT in Riwayat
        $resWbTab1 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'whistleblowing']));
        $resWbTab1->assertStatus(200);
        $resWbTab1->assertSee('WB-FLOW-001');

        $resSaya1 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $resSaya1->assertStatus(200);
        $resSaya1->assertDontSee('WB-FLOW-001');

        $resHistory1 = $this->actingAs($kabag)->get(route('kabag.history'));
        $resHistory1->assertStatus(200);
        $resHistory1->assertDontSee('WB-FLOW-001');

        // 2. Kabag clicks Kerjakan Mandiri
        $postSelf = $this->actingAs($kabag)->post(route('kabag.self-execute', $report));
        $postSelf->assertStatus(302);

        $report->refresh();
        $this->assertEquals('proses', $report->status);
        $this->assertEquals($kabag->id, $report->handler_id);

        // Now in Laporan Saya (diproses):
        // In Laporan Whistleblowing tab: NOT visible
        $resWbTab2 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'whistleblowing']));
        $resWbTab2->assertStatus(200);
        $resWbTab2->assertDontSee('WB-FLOW-001');

        // In Laporan Saya tab: IS visible
        $resSaya2 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $resSaya2->assertStatus(200);
        $resSaya2->assertSee('WB-FLOW-001');

        // In Riwayat: NOT visible
        $resHistory2 = $this->actingAs($kabag)->get(route('kabag.history'));
        $resHistory2->assertStatus(200);
        $resHistory2->assertDontSee('WB-FLOW-001');

        // 3. Kabag finishes report (Selesaikan Laporan)
        $postComplete = $this->actingAs($kabag)->post(route('kabag.complete-self', $report), [
            'notes' => 'Tindak lanjut laporan selesai dituntaskan.',
        ]);
        $postComplete->assertRedirect(route('kabag.history'));

        $report->refresh();
        $this->assertEquals('selesai', $report->status);

        // Now completed:
        // In Laporan Whistleblowing tab: NOT visible anymore
        $resWbTab3 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'whistleblowing']));
        $resWbTab3->assertStatus(200);
        $resWbTab3->assertDontSee('WB-FLOW-001');

        // In Laporan Saya tab: NOT visible anymore
        $resSaya3 = $this->actingAs($kabag)->get(route('kabag.whistleblowing', ['tab' => 'saya']));
        $resSaya3->assertStatus(200);
        $resSaya3->assertDontSee('WB-FLOW-001');

        // In Riwayat: IS visible!
        $resHistory3 = $this->actingAs($kabag)->get(route('kabag.history'));
        $resHistory3->assertStatus(200);
        $resHistory3->assertSee('WB-FLOW-001');
    }
}

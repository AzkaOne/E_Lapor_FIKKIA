<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetugasDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dashboard_renders_matching_kasubag_layout_with_petugas_sidebar(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report1 = Report::create([
            'ticket_code' => 'LP-PTG-001',
            'title' => 'AC Mati Gedung A',
            'description' => 'AC tidak dingin di ruang 101',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        $report2 = Report::create([
            'ticket_code' => 'LP-PTG-002',
            'title' => 'Pintu Rusak Lab',
            'description' => 'Engsel pintu patah',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'proses',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);

        // Sidebar Petugas items (Dashboard, Laporan Masuk, Riwayat)
        $response->assertSee('Dashboard');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Riwayat');

        // Must NOT see Kasubag/Kabag menus
        $response->assertDontSee('Manajemen Kasubag');
        $response->assertDontSee('Manajemen Petugas');
        $response->assertDontSee('Laporan Whistleblower');

        // Header and Greeting
        $response->assertSee('E-Lapor FIKKIA');
        $response->assertSee('Budi Petugas Sarpras');

        // Top Section: Donut Chart, Stat cards, Notes
        $response->assertSee('Laporan Per Bidang');
        $response->assertSee('Perlu penanganan');
        $response->assertSee('Dalam Penanganan');
        $response->assertSee('Menunggu Verifikasi');
        $response->assertSee('Catatan / Komentar');

        // Table & Side Widgets
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Informasi Hari Ini');
        $response->assertSee('Aktivitas Terbaru');

        // Reports and Action buttons
        $response->assertSee('LP-PTG-001');
        $response->assertSee('AC Mati Gedung A');
        $response->assertSee('Detail');
        $response->assertDontSee('Mulai Kerjakan');

        $response->assertSee('LP-PTG-002');
        $response->assertSee('Pintu Rusak Lab');
        $response->assertDontSee('Lanjutkan');
    }

    public function test_petugas_laporan_masuk_renders_without_revisi_box(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-PTG-INBOX-1',
            'title' => 'Lampu Panggung Rusak',
            'description' => 'Lampu padam',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($petugas)->get('/petugas/laporan-masuk');

        $response->assertStatus(200);

        // Tabs
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Laporan Saya');

        // 5 Stat boxes must be present
        $response->assertSee('Total Laporan');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Laporan Terlaksana');
        $response->assertSee('Laporan Tertunda');
        $response->assertSee('Laporan Proses');

        // Box Revisi must NOT be present
        $response->assertDontSee('Laporan Revisi');

        // Table and Actions: Only Detail
        $response->assertSee('LP-PTG-INBOX-1');
        $response->assertSee('Lampu Panggung Rusak');
        $response->assertSee('Detail');
        $response->assertDontSee('Mulai Kerjakan');
    }

    public function test_petugas_laporan_saya_renders_without_filter_dropdowns_and_stat_boxes(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-MY-001',
            'title' => 'Ganti Keran Toilet',
            'description' => 'Keran patah di lantai 2',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'proses',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($petugas)->get('/petugas/laporan-saya');

        $response->assertStatus(200);

        // Tabs
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Laporan Saya');

        // Search input must be present
        $response->assertSee('placeholder="Cari judul laporan, nama pelapor, atau nomor tiket..."', false);

        // Filter status, filter date, and reset button must NOT be present
        $response->assertDontSee('title="Filter Berdasarkan Status Laporan"', false);
        $response->assertDontSee('title="Filter Berdasarkan Tanggal"', false);
        $response->assertDontSee('Reset Semua Filter');
        $response->assertDontSee('Semua Status');

        // Stat boxes must NOT be present
        $response->assertDontSee('Total Laporan');
        $response->assertDontSee('Laporan Terlaksana');
        $response->assertDontSee('Laporan Tertunda');
        $response->assertDontSee('Laporan Proses');
        $response->assertDontSee('Laporan Revisi');

        // Table & Actions: Only Detail
        $response->assertSee('LP-MY-001');
        $response->assertSee('Ganti Keran Toilet');
        $response->assertSee('Detail');
    }

    public function test_petugas_can_view_detail_with_only_two_action_menus(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-DETAIL-001',
            'title' => 'Genting Bocor Gedung C',
            'description' => 'Air menetes deras saat hujan di lab komputer',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($petugas)->get("/petugas/laporan/{$report->id}");

        $response->assertStatus(200);

        // Header / Title Panel Petugas
        $response->assertSee('Detail Laporan #LP-DETAIL-001 - Panel PETUGAS E-Lapor FIKKIA', false);

        // Action Menus: MUST have Kerjakan Mandiri and Tolak Laporan
        $response->assertSee('Kerjakan Mandiri');
        $response->assertSee('Mulai kerjakan penugasan di lapangan');
        $response->assertSee('Tolak Laporan');
        $response->assertSee('Tolak dan kembalikan ke Kabag');

        // MUST have rejection evidence file input
        $response->assertSee('name="rejection_evidence"', false);
        $response->assertSee('Bukti Penolakan');

        // Action Menus: MUST NOT have Disposisi Ke Kasubag or Kirim ke Petugas
        $response->assertDontSee('Disposisi Ke Kasubag');
        $response->assertDontSee('Kirim ke Petugas');
    }

    public function test_petugas_can_self_execute_report_from_detail(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-EXEC-001',
            'title' => 'Kipas Angin Rusak',
            'description' => 'Mati total tidak mau berputar',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        // Before execution: report is in Laporan Masuk, but NOT in Laporan Saya
        $responseInbox = $this->actingAs($petugas)->get('/petugas/laporan-masuk');
        $responseInbox->assertSee('LP-EXEC-001');

        $responseMy = $this->actingAs($petugas)->get('/petugas/laporan-saya');
        $responseMy->assertDontSee('LP-EXEC-001');

        // Detail page: NO javascript confirm alert for petugas
        $responseDetail = $this->actingAs($petugas)->get("/petugas/laporan/{$report->id}");
        $responseDetail->assertDontSee('onsubmit="return confirm(\'Apakah Anda yakin ingin memproses laporan ini secara mandiri?\')"', false);

        // Execute report
        $response = $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/kerjakan-sendiri");

        $response->assertRedirect('/petugas/laporan-saya');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'proses',
            'handler_id' => $petugas->id,
        ]);

        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'sender_id' => $petugas->id,
            'receiver_id' => $petugas->id,
        ]);

        // After execution: report has MOVED to Laporan Saya and is GONE from default Laporan Masuk
        $responseMyAfter = $this->actingAs($petugas)->get('/petugas/laporan-saya');
        $responseMyAfter->assertSee('LP-EXEC-001');

        $responseInboxAfter = $this->actingAs($petugas)->get('/petugas/laporan-masuk');
        $responseInboxAfter->assertDontSee('LP-EXEC-001');

        // And detail view shows "Pilih Tindakan" form for petugas
        $responseDetailAfter = $this->actingAs($petugas)->get("/petugas/laporan/{$report->id}");
        $responseDetailAfter->assertSee('Pilih Tindakan');
        $responseDetailAfter->assertSee('Laporan Tertunda');
        $responseDetailAfter->assertSee('Laporan Terlaksana');
    }

    public function test_petugas_can_reject_report_with_reason_and_evidence_to_kabag(): void
    {
        Storage::fake('public');

        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create([
            'role' => 'kabag',
            'name' => 'Pak Fuad KABAG',
            'position' => 'Kepala Bagian',
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-REJECT-001',
            'title' => 'Penggantian Proyektor Aula',
            'description' => 'Proyektor rusak minta diganti',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        $fakeFile = UploadedFile::fake()->create('foto_lokasi_valid.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/tolak", [
            'rejection_reason' => 'Bukan kerusakan fisik, hanya saklar listrik aula belum dihidupkan.',
            'rejection_evidence' => $fakeFile,
        ]);

        $response->assertRedirect('/petugas/laporan-masuk');

        // Check report status
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'ditolak',
            'rejection_reason' => 'Bukan kerusakan fisik, hanya saklar listrik aula belum dihidupkan.',
        ]);

        // Check disposition was redirected to KABAG
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'sender_id' => $petugas->id,
            'receiver_id' => $kabag->id,
        ]);

        // Check evidence was stored
        $this->assertDatabaseHas('report_evidences', [
            'report_id' => $report->id,
            'uploaded_by_id' => $petugas->id,
            'type' => 'rejection',
        ]);

        $evidence = ReportEvidence::where('report_id', $report->id)->first();
        Storage::disk('public')->assertExists($evidence->file_path);
    }
}

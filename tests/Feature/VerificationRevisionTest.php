<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class VerificationRevisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_view_verification_queue(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        Report::create([
            'ticket_code' => 'LP-VERIF-1',
            'title' => 'Penggantian Saklar',
            'description' => 'Saklar rusak di lab komputer',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($kabag)->get('/kabag/verifikasi');
        $response->assertStatus(200);
        $response->assertSee('Penggantian Saklar');
    }

    public function test_kabag_can_approve_completed_report(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-2',
            'title' => 'Perbaikan Pintu',
            'description' => 'Engsel pintu rusak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Pekerjaan rapi dan sesuai.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'notes' => 'Laporan diverifikasi selesai oleh KABAG: Pekerjaan rapi dan sesuai.',
        ]);
    }

    public function test_kabag_can_approve_postponed_report_with_explanation(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-3',
            'title' => 'Pengadaan Proyektor',
            'description' => 'Proyektor ruang sidang mati total',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'tertunda',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Disetujui ditunda sampai anggaran tahun depan.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->assertEquals('Disetujui ditunda sampai anggaran tahun depan.', $report->fresh()->postponed_reason);
    }

    public function test_kabag_can_request_revision_back_to_kasubag(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-4',
            'title' => 'Pengecatan Tembok',
            'description' => 'Tembok mengelupas di koridor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/revisi", [
            'notes' => 'Cat dinding masih belum merata di bagian atas. Mohon perbaiki.',
        ]);

        $response->assertRedirect();
        $fresh = $report->fresh();
        // Returned to didisposisi so Kasubag can instruct petugas for revision
        $this->assertEquals('didisposisi', $fresh->status);
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'notes' => 'Cat dinding masih belum merata di bagian atas. Mohon perbaiki.',
        ]);
    }

    public function test_kabag_verifikasi_view_renders_matching_laporan_pelayanan_layout_and_only_verification_reports(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);

        // 1. Report needing verification (terlaksana)
        Report::create([
            'ticket_code' => 'LP-VERIF-TL',
            'title' => 'Laporan Selesai Terlaksana',
            'description' => 'Telah selesai diperbaiki',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        // 2. Report needing verification (tertunda)
        Report::create([
            'ticket_code' => 'LP-VERIF-TT',
            'title' => 'Laporan Ditunda Sparepart',
            'description' => 'Menunggu pengadaan suku cadang',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'tertunda',
        ]);

        // 3. Regular report not in verification
        Report::create([
            'ticket_code' => 'LP-REG-1',
            'title' => 'Laporan Masih Diajukan',
            'description' => 'Baru diajukan',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->get('/kabag/verifikasi');
        $response->assertStatus(200);
        $response->assertViewIs('kabag.verifikasi_kabag');

        // Should see verification reports
        $response->assertSee('Laporan Selesai Terlaksana');
        $response->assertSee('Laporan Ditunda Sparepart');
        $response->assertSee('Terlaksana');
        $response->assertSee('Tertunda');

        // Should NOT see non-verification report
        $response->assertDontSee('Laporan Masih Diajukan');

        // Check tabs existence and layout matching laporan_pelayanan
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Verifikasi laporan');

        // Check filter status terlaksana only
        $resFilterTl = $this->actingAs($kabag)->get('/kabag/verifikasi?status=terlaksana');
        $resFilterTl->assertSee('Laporan Selesai Terlaksana');
        $resFilterTl->assertDontSee('Laporan Ditunda Sparepart');

        // Check filter status tertunda only
        $resFilterTt = $this->actingAs($kabag)->get('/kabag/verifikasi?status=tertunda');
        $resFilterTt->assertSee('Laporan Ditunda Sparepart');
        $resFilterTt->assertDontSee('Laporan Selesai Terlaksana');

        // Check that table has only 'Lihat Detail' button
        $response->assertSee('Lihat Detail');
    }

    public function test_kabag_detail_shows_verification_action_box_with_selesai_revisi_and_options(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['name' => 'Kasubag Sarpras', 'role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-DETAIL',
            'title' => 'Laporan Siap Verifikasi',
            'description' => 'Kerusakan telah diperbaiki teknisi',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
            'resolution_notes' => 'Pekerjaan telah rampung 100%.',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.show', $report));
        $response->assertStatus(200);

        // Verification box headers & buttons
        $response->assertSee('Verifikasi Laporan');
        $response->assertSee('Selesai');
        $response->assertSee('Revisi');

        // Optional inputs & checkboxes
        $response->assertSee('Catatan / Komentar');
        $response->assertSee('Bukti Laporan');
        $response->assertSee('Tampilkan bukti laporan kepada pelapor');
        $response->assertSee('Tampilkan komentar petugas kepada pelapor');

        // Kasubag selection in revisi list
        $response->assertSee('Kasubag Sarpras');
    }

    public function test_kabag_can_verify_selesai_with_visibility_flags_and_evidence(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-COMPLETE',
            'title' => 'Laporan AC Bocor',
            'description' => 'AC bocor di ruang kuliah',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('bukti_verifikasi.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Verifikasi selesai dan disetujui.',
            'evidence' => $file,
            'show_evidence_to_pelapor' => '1',
            // show_notes_to_pelapor omitted/unchecked
        ]);

        $response->assertRedirect();
        $fresh = $report->fresh();
        $this->assertEquals('selesai', $fresh->status);
        $this->assertTrue($fresh->show_evidence_to_pelapor);
        $this->assertFalse($fresh->show_notes_to_pelapor);
        $this->assertDatabaseHas('report_evidences', [
            'report_id' => $report->id,
            'uploaded_by_id' => $kabag->id,
            'type' => 'resolution',
        ]);
    }

    public function test_kabag_can_revisi_with_target_kasubag_and_flags(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['name' => 'Kasubag Sarpras', 'role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-VERIF-REVISI-TARGET',
            'title' => 'Laporan Lampu Koridor',
            'description' => 'Lampu koridor mati',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'verifikasi_kabag',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/revisi", [
            'notes' => 'Lampu masih redup, mohon ganti dengan daya lebih tinggi.',
            'receiver_id' => $kasubag->id,
        ]);

        $response->assertRedirect();
        $fresh = $report->fresh();
        $this->assertEquals('didisposisi', $fresh->status);
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'receiver_id' => $kasubag->id,
            'notes' => 'Lampu masih redup, mohon ganti dengan daya lebih tinggi.',
        ]);
    }
}
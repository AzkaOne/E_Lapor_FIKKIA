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
}
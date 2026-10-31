<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class KabagDispositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_cannot_directly_assign_sub_department_report_to_petugas(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $petugasSarpras = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TEST1',
            'title' => 'Lampu Mati',
            'description' => 'Lampu mati di koridor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/disposisi", [
            'target_type' => 'petugas',
            'receiver_id' => $petugasSarpras->id,
            'notes' => 'Tolong kerjakan',
        ]);

        $response->assertSessionHasErrors('receiver_id');
        $this->assertEquals('diajukan', $report->fresh()->status);
    }

    public function test_kabag_can_dispose_sub_department_report_to_kasubag(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubagSarpras = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TEST2',
            'title' => 'Pipa Bocor',
            'description' => 'Pipa bocor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/disposisi", [
            'target_type' => 'kasubag',
            'receiver_id' => $kasubagSarpras->id,
            'notes' => 'Mohon ditindaklanjuti tim sarpras.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('didisposisi', $report->fresh()->status);
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => $kasubagSarpras->id,
            'notes' => 'Mohon ditindaklanjuti tim sarpras.',
        ]);
    }

    public function test_kabag_can_dispose_dekanat_report_to_petugas_kabag(): void
    {
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);
        $kabag = User::factory()->create(['role' => 'kabag', 'sub_department_id' => $dekanat->id]);
        $petugasKabag = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $dekanat->id]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TEST3',
            'title' => 'Berkas Dekanat',
            'description' => 'Berkas dekanat',
            'type' => 'pelayanan',
            'sub_department_id' => $dekanat->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/disposisi", [
            'target_type' => 'petugas',
            'receiver_id' => $petugasKabag->id,
            'notes' => 'Siapkan berkasnya.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('diterima', $report->fresh()->status);
        $this->assertEquals($petugasKabag->id, $report->fresh()->handler_id);
    }

    public function test_kabag_can_reject_report_with_reason(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TEST4',
            'title' => 'Laporan Tidak Jelas',
            'description' => 'tes',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/tolak", [
            'rejection_reason' => 'Laporan bukan merupakan wewenang kampus.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('ditolak', $report->fresh()->status);
        $this->assertEquals('Laporan bukan merupakan wewenang kampus.', $report->fresh()->rejection_reason);
    }

    public function test_kabag_can_self_execute_report_directly_to_selesai(): void
    {
        Storage::fake('public');
        $kabag = User::factory()->create(['role' => 'kabag']);
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TEST5',
            'title' => 'Surat Keputusan Dekanat',
            'description' => 'SK dekanat',
            'type' => 'pelayanan',
            'sub_department_id' => $dekanat->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        // 1. Choose self-execution
        $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/kerjakan-sendiri");
        $this->assertEquals('proses', $report->fresh()->status);
        $this->assertEquals($kabag->id, $report->fresh()->handler_id);

        // 2. Complete directly without verification
        $response = $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/selesai-mandiri", [
            'notes' => 'Sudah ditandatangani oleh Kabag langsung.',
            'evidence' => UploadedFile::fake()->image('ttd.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->assertEquals('terlaksana', $report->fresh()->execution_result);
    }

    public function test_kabag_handles_whistleblowing_exclusively_and_other_roles_forbidden(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $kasubag = User::factory()->create(['role' => 'kasubag']);

        $wb = Report::create([
            'ticket_code' => 'WB-2026-SECRET',
            'type' => 'whistleblowing',
            'campus_location' => 'sobo',
            'title' => 'Rahasia Kampus',
            'description' => 'Ada indikasi pungli',
            'status' => 'diajukan',
        ]);

        // Kasubag access is forbidden
        $this->actingAs($kasubag)->get('/kabag/whistleblowing')->assertStatus(403);

        // Kabag starts process
        $this->actingAs($kabag)->post("/kabag/whistleblowing/{$wb->id}/proses");
        $this->assertEquals('proses', $wb->fresh()->status);

        // Kabag completes whistleblowing
        $this->actingAs($kabag)->post("/kabag/whistleblowing/{$wb->id}/selesai", [
            'resolution_notes' => 'Sudah dilakukan pemanggilan dan klarifikasi resmi.',
        ]);
        $this->assertEquals('selesai', $wb->fresh()->status);
        $this->assertEquals('Sudah dilakukan pemanggilan dan klarifikasi resmi.', $wb->fresh()->resolution_notes);
    }
}

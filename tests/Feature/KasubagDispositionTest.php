<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class KasubagDispositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_dashboard_only_shows_unit_reports(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $sdm = SubDepartment::create(['name' => 'SDM', 'code' => 'SDM']);

        $kasubagSarpras = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $repSarpras = Report::create([
            'ticket_code' => 'LP-SARPRAS-1',
            'title' => 'Kerusakan Sarpras',
            'description' => 'Kerusakan sarpras',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'didisposisi',
        ]);

        $repSdm = Report::create([
            'ticket_code' => 'LP-SDM-1',
            'title' => 'Pengaduan SDM',
            'description' => 'Pengaduan SDM',
            'type' => 'pelayanan',
            'sub_department_id' => $sdm->id,
            'campus_location' => 'sobo',
            'status' => 'didisposisi',
        ]);

        $response = $this->actingAs($kasubagSarpras)->get('/kasubag/dashboard');
        $response->assertStatus(200);
        $response->assertSee('LP-SARPRAS-1');
        $response->assertDontSee('LP-SDM-1');
    }

    public function test_kasubag_can_dispose_to_petugas_in_same_unit(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-DISP-PETUGAS',
            'title' => 'Ganti Lampu',
            'description' => 'Ganti lampu',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'didisposisi',
        ]);

        $response = $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/disposisi", [
            'receiver_id' => $petugas->id,
            'notes' => 'Segera ganti bohlam.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('diterima', $report->fresh()->status);
        $this->assertEquals($petugas->id, $report->fresh()->handler_id);
    }

    public function test_kasubag_cannot_dispose_to_petugas_of_another_unit(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $sdm = SubDepartment::create(['name' => 'SDM', 'code' => 'SDM']);

        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugasLain = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sdm->id]);

        $report = Report::create([
            'ticket_code' => 'LP-INVALID-PETUGAS',
            'title' => 'Lampu Rusak',
            'description' => 'Lampu rusak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'didisposisi',
        ]);

        $response = $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/disposisi", [
            'receiver_id' => $petugasLain->id,
            'notes' => 'Tolong kerjakan.',
        ]);

        $response->assertSessionHasErrors('receiver_id');
        $this->assertEquals('didisposisi', $report->fresh()->status);
    }

    public function test_kasubag_can_return_misrouted_report_to_kabag(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-MISROUTED',
            'title' => 'Salah Alamat',
            'description' => 'Bukan bidang sarpras',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'didisposisi',
        ]);

        $response = $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/kembalikan", [
            'notes' => 'Ini ranah Kemahasiswaan, bukan Sarpras.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('diajukan', $report->fresh()->status);
        $this->assertDatabaseHas('report_dispositions', ['notes' => 'Ini ranah Kemahasiswaan, bukan Sarpras.']);
    }

    public function test_kasubag_can_self_execute_and_submit_for_kabag_verification(): void
    {
        Storage::fake('public');
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-SELF-KASUBAG',
            'title' => 'Audit Sarpras Langsung',
            'description' => 'Audit',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'didisposisi',
        ]);

        // Start self-execution
        $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/kerjakan-sendiri");
        $this->assertEquals('proses', $report->fresh()->status);
        $this->assertEquals($kasubag->id, $report->fresh()->handler_id);

        // Submit for Kabag verification
        $response = $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/kirim-verifikasi", [
            'notes' => 'Pemeriksaan telah selesai dikerjakan Kasubag.',
            'execution_result' => 'terlaksana',
            'evidence' => UploadedFile::fake()->image('audit.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);
        $this->assertEquals('terlaksana', $report->fresh()->execution_result);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class E2EReportLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_report_lifecycle_flow(): void
    {
        Storage::fake('public');

        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'pelapor@unair.ac.id']);

        // 1. Pelapor submits report
        $response = $this->actingAs($pelapor)->post('/pelapor/laporan', [
            'title' => 'Gedung Mojo Bocor',
            'campus_location' => 'mojo',
            'sub_department_id' => $sarpras->id,
            'description' => 'Bocor di lantai 2',
        ]);
        $response->assertRedirect();
        $report = Report::latest()->first();
        $this->assertEquals('diajukan', $report->status);

        // 2. Kabag disposes to Kasubag
        $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/disposisi", [
            'target_type' => 'kasubag',
            'receiver_id' => $kasubag->id,
            'notes' => 'Segera cek lapangan.',
        ]);
        $this->assertEquals('didisposisi', $report->fresh()->status);

        // 3. Kasubag disposes to Petugas
        $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/disposisi", [
            'receiver_id' => $petugas->id,
            'notes' => 'Bawa alat tambal.',
        ]);
        $this->assertEquals('diterima', $report->fresh()->status);

        // 4. Petugas starts work
        $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/mulai");
        $this->assertEquals('proses', $report->fresh()->status);

        // 5. Petugas finishes work
        $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/selesai", [
            'notes' => 'Sudah ditambal rapi.',
            'execution_result' => 'terlaksana',
            'evidence' => UploadedFile::fake()->image('bukti.jpg'),
        ]);
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);

        // 6. Kabag approves verification
        $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Pekerjaan baik.',
        ]);
        $this->assertEquals('selesai', $report->fresh()->status);

        // 7. Pelapor provides rating
        $this->actingAs($pelapor)->post("/pelapor/laporan/{$report->id}/rating", [
            'rating' => 5,
        ]);
        $this->assertEquals(5, $report->fresh()->rating);
    }

    public function test_dekanat_direct_officer_lifecycle_flow(): void
    {
        Storage::fake('public');

        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEKANAT']);
        $kabag = User::factory()->create(['role' => 'kabag', 'sub_department_id' => $dekanat->id]);
        $petugasDekanat = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $dekanat->id]);
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'dosen@unair.ac.id']);

        // 1. Pelapor submits Dekanat report
        $this->actingAs($pelapor)->post('/pelapor/laporan', [
            'title' => 'Permintaan Jadwal Rapat Pimpinan',
            'campus_location' => 'giri',
            'sub_department_id' => $dekanat->id,
            'description' => 'Mohon diagendakan rapat senat fakultas.',
        ]);
        $report = Report::latest()->first();

        // 2. Kabag assigns directly to Petugas Dekanat
        $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/disposisi", [
            'target_type' => 'petugas',
            'receiver_id' => $petugasDekanat->id,
            'notes' => 'Tolong buatkan undangan rapat.',
        ]);
        $this->assertEquals('diterima', $report->fresh()->status);

        // 3. Petugas starts and finishes
        $this->actingAs($petugasDekanat)->post("/petugas/laporan/{$report->id}/mulai");
        $this->actingAs($petugasDekanat)->post("/petugas/laporan/{$report->id}/selesai", [
            'notes' => 'Undangan rapat senat telah didistribusikan.',
            'execution_result' => 'terlaksana',
        ]);
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);

        // 4. Kabag approves
        $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Siap dilaksanakan.',
        ]);
        $this->assertEquals('selesai', $report->fresh()->status);
    }

    public function test_whistleblowing_anonymous_lifecycle_flow(): void
    {
        Storage::fake('public');
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        // 1. Submit anonymous WB report
        $postResponse = $this->post('/whistleblowing', [
            'title' => 'Dugaan Penyalahgunaan Inventaris',
            'campus_location' => 'sobo',
            'sub_department_id' => $sarpras->id,
            'description' => 'Ada penggunaan aset inventaris tanpa izin tertulis.',
        ]);
        $postResponse->assertSessionHas('ticket_code');
        $ticketCode = session('ticket_code');

        $report = Report::where('ticket_code', $ticketCode)->first();
        $this->assertNotNull($report);
        $this->assertEquals('diajukan', $report->status);
        $this->assertEquals('whistleblowing', $report->type);

        // 2. Kabag processes WB report
        $this->actingAs($kabag)->post("/kabag/whistleblowing/{$report->id}/proses");
        $this->assertEquals('proses', $report->fresh()->status);

        // 3. Kabag finishes WB report
        $this->actingAs($kabag)->post("/kabag/whistleblowing/{$report->id}/selesai", [
            'resolution_notes' => 'Sudah dilakukan audit internal dan pembinaan.',
        ]);
        $this->assertEquals('selesai', $report->fresh()->status);

        // 4. Anonymous public tracking
        $trackResponse = $this->get("/tracking?ticket_code={$ticketCode}");
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($ticketCode);
        $trackResponse->assertSee('Sudah dilakukan audit internal dan pembinaan.');
    }
}
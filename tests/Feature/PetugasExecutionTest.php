<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class PetugasExecutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_start_and_submit_completed_report_for_verification(): void
    {
        Storage::fake('public');
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-PETUGAS-1',
            'title' => 'Perbaikan Kran Air',
            'description' => 'Kran bocor di lantai 1',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        // Start work
        $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/mulai");
        $this->assertEquals('proses', $report->fresh()->status);

        // Submit finished work
        $response = $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/selesai", [
            'notes' => 'Kran air sudah diganti dengan unit baru.',
            'execution_result' => 'terlaksana',
            'evidence' => UploadedFile::fake()->image('kran.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);
        $this->assertEquals('terlaksana', $report->fresh()->execution_result);
        $this->assertEquals('Kran air sudah diganti dengan unit baru.', $report->fresh()->resolution_notes);
        $this->assertCount(1, $report->evidences->where('type', 'resolution'));
    }

    public function test_petugas_cannot_execute_unassigned_report(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $petugas1 = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);
        $petugas2 = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $report = Report::create([
            'ticket_code' => 'LP-UNASSIGNED',
            'title' => 'Lampu Putus',
            'description' => 'Lampu putus',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diterima',
            'handler_id' => $petugas1->id,
        ]);

        $response = $this->actingAs($petugas2)->post("/petugas/laporan/{$report->id}/mulai");
        $response->assertStatus(403);
    }
}

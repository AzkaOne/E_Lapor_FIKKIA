<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class PelaporReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelapor_can_submit_service_report(): void
    {
        Storage::fake('public');

        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $response = $this->actingAs($pelapor)->post('/pelapor/laporan', [
            'title' => 'AC Mati di Ruang Kuliah Mojo 201',
            'campus_location' => 'mojo',
            'sub_department_id' => $subDept->id,
            'description' => 'AC tidak dingin sama sekali.',
            'evidence' => UploadedFile::fake()->image('ac.jpg'),
        ]);

        $response->assertRedirect('/pelapor/dashboard');
        $this->assertDatabaseHas('reports', [
            'title' => 'AC Mati di Ruang Kuliah Mojo 201',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        $report = Report::where('title', 'AC Mati di Ruang Kuliah Mojo 201')->first();
        $this->assertStringStartsWith('LP-', $report->ticket_code);
        $this->assertCount(1, $report->evidences);
    }

    public function test_pelapor_can_rate_completed_terlaksana_report(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'ticket_code' => 'LP-2026-RATED',
            'title' => 'AC Rusak Sobo',
            'campus_location' => 'sobo',
            'description' => 'AC rusak',
            'type' => 'pelayanan',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($pelapor)->post("/pelapor/laporan/{$report->id}/rating", [
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertEquals(5, $report->fresh()->rating);
    }

    public function test_pelapor_can_navigate_to_detail_pelapor_from_tables_and_view_rating_box_when_completed(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'ticket_code' => 'LP-2026-DETAIL',
            'title' => 'Kerusakan Meja Kuliah',
            'campus_location' => 'sobo',
            'description' => 'Meja patah di kelas',
            'type' => 'pelayanan',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        // Status table links to detail_pelapor
        $statusRes = $this->actingAs($pelapor)->get('/pelapor/status?status=all');
        $statusRes->assertStatus(200);
        $statusRes->assertSee(route('pelapor.show', $report));
        $statusRes->assertDontSee('openModal(');

        // Riwayat table links to detail_pelapor
        $historyRes = $this->actingAs($pelapor)->get('/pelapor/riwayat');
        $historyRes->assertStatus(200);
        $historyRes->assertSee(route('pelapor.show', $report));
        $historyRes->assertDontSee('openModal(');

        // Detail pelapor page renders with completion banner and star rating box underneath
        $detailRes = $this->actingAs($pelapor)->get(route('pelapor.show', $report));
        $detailRes->assertStatus(200);
        $detailRes->assertViewIs('pelapor.detail_pelapor');
        $detailRes->assertSee('Laporan ini telah selesai.');
        $detailRes->assertSee('Beri Penilaian Pelayanan');
        $detailRes->assertSee('Berapa bintang untuk penanganan laporan ini?');
        $detailRes->assertSee('Kirim Penilaian');

        // Submit rating 4
        $rateRes = $this->actingAs($pelapor)->post(route('pelapor.rate', $report), [
            'rating' => 4,
        ]);
        $rateRes->assertRedirect();

        // Refresh detail page to verify rating box display
        $detailResAfter = $this->actingAs($pelapor)->get(route('pelapor.show', $report));
        $detailResAfter->assertStatus(200);
        $detailResAfter->assertSee('Penilaian Anda: 4/5 Bintang');
    }

    public function test_pelapor_cannot_rate_rejected_or_non_terlaksana_report(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $rejectedReport = Report::create([
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'ticket_code' => 'LP-2026-REJ',
            'title' => 'Laporan Palsu',
            'campus_location' => 'sobo',
            'description' => 'tes',
            'type' => 'pelayanan',
            'status' => 'ditolak',
            'rejection_reason' => 'Tidak valid',
        ]);

        $response = $this->actingAs($pelapor)->post("/pelapor/laporan/{$rejectedReport->id}/rating", [
            'rating' => 5,
        ]);

        $response->assertStatus(422);
        $this->assertNull($rejectedReport->fresh()->rating);
    }

    public function test_submitting_report_displays_success_popup_on_dashboard(): void
    {
        Storage::fake('public');

        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $response = $this->actingAs($pelapor)->post('/pelapor/laporan', [
            'title' => 'Lampu Koridor Rusak',
            'campus_location' => 'sobo',
            'sub_department_id' => $subDept->id,
            'description' => 'Lampu padam sejak kemarin.',
        ]);

        $response->assertRedirect('/pelapor/dashboard');
        $response->assertSessionHas('success');
        $response->assertSessionHas('ticket_code');

        $report = Report::where('title', 'Lampu Koridor Rusak')->first();

        $dashboardWithFlash = $this->actingAs($pelapor)->withSession([
            'success' => 'Laporan pelayanan berhasil dikirim dan siap ditinjau oleh KABAG.',
            'ticket_code' => $report->ticket_code,
            'report_title' => $report->title,
        ])->get('/pelapor/dashboard');

        $dashboardWithFlash->assertStatus(200);
        $dashboardWithFlash->assertSee('reportStatusModal');
        $dashboardWithFlash->assertSee('Laporan Berhasil Terkirim!');
        $dashboardWithFlash->assertSee($report->ticket_code);
        $dashboardWithFlash->assertSee('Pantau Status Laporan');
    }

    public function test_submitting_report_with_errors_displays_error_popup_on_dashboard(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);

        $dashboardWithError = $this->actingAs($pelapor)->withSession([
            'error' => 'Gagal mengirim laporan. Terjadi gangguan pada koneksi.',
        ])->get('/pelapor/dashboard');

        $dashboardWithError->assertStatus(200);
        $dashboardWithError->assertSee('reportStatusModal');
        $dashboardWithError->assertSee('Pengiriman Laporan Gagal');
        $dashboardWithError->assertSee('Gagal mengirim laporan. Terjadi gangguan pada koneksi.');
        $dashboardWithError->assertSee('Periksa dan Lengkapi Data');
    }
}

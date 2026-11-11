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

    public function test_kasubag_dashboard_header_and_sidebar_components(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'name' => 'Mas Gatot Kasubag',
            'position' => 'Kepala Sub Bagian',
            'sub_department_id' => $sarpras->id,
        ]);

        $response = $this->actingAs($kasubag)->get('/kasubag/dashboard');

        $response->assertStatus(200);

        // Sidebar Kasubag items
        $response->assertSee('Dashboard');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Riwayat Laporan');
        $response->assertSee('Manajemen Petugas');

        // Should not see Kabag-only sidebar items
        $response->assertDontSee('Laporan Whistleblower');
        $response->assertDontSee('Manajemen Kasubag');

        // Top section cards
        $response->assertSee('E-Lapor FIKKIA');
        $response->assertSee('Laporan Per Bidang');
        $response->assertSee('Perlu penanganan');
        $response->assertSee('Dalam Penanganan');
        $response->assertSee('Menunggu Verifikasi');
        $response->assertSee('Catatan / Komentar');

        // Header user info
        $response->assertSee('Mas Gatot Kasubag');
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

    public function test_kasubag_detail_page_renders_detail_kabag_with_three_action_menus(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);
        $sdm = SubDepartment::create(['name' => 'SDM', 'code' => 'SDM']);

        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'name' => 'Mas Gatot Kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        $petugasSarpras = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Rusdiana Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
        ]);

        $petugasSdm = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Doni Petugas SDM',
            'sub_department_id' => $sdm->id,
        ]);

        $kabag = User::factory()->create(['role' => 'kabag']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-KASUBAGVIEW',
            'title' => 'AC Rusak Ruang 102',
            'description' => 'AC bocor air',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'didisposisi',
        ]);

        // Report has a disposition from Kabag (this previously triggered the variable overwrite bug)
        \App\Models\ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => $kasubag->id,
            'notes' => 'Tolong segera ditindaklanjuti tim sarpras.',
        ]);

        $response = $this->actingAs($kasubag)->get("/kasubag/laporan/{$report->id}");

        $response->assertStatus(200);
        $response->assertViewIs('kabag.detail_Kabag');

        // 3 action menus for Kasubag
        $response->assertSee('Kirim ke Petugas');
        $response->assertSee('Kerjakan Mandiri');
        $response->assertSee('Tolak Laporan');

        // Kasubag MUST NOT see 'Disposisi Ke Kasubag'
        $response->assertDontSee('Disposisi Ke Kasubag');

        // Form action for Kirim ke Petugas MUST point to kasubag route, NOT kabag route
        $response->assertSee(route('kasubag.dispose', $report));
        $response->assertDontSee(route('kabag.dispose', $report));

        // Only officers matching report's sub_department are displayed
        $response->assertSee('Rusdiana Petugas Sarpras');
        $response->assertDontSee('Doni Petugas SDM');
    }

    public function test_kasubag_can_reject_report_and_redirect_to_kabag(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag', 'name' => 'Fuad Kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'name' => 'Mas Gatot Kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-REJECT',
            'title' => 'Laporan Salah Sub Bagian',
            'description' => 'Bukan tugas kami',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'didisposisi',
        ]);

        $response = $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/tolak", [
            'rejection_reason' => 'Laporan tidak relevan dan ditolak oleh Kasubag.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('ditolak', $report->fresh()->status);
        $this->assertNull($report->fresh()->handler_id);
        $this->assertEquals('Laporan tidak relevan dan ditolak oleh Kasubag.', $report->fresh()->rejection_reason);

        // Verify disposition redirects to Kabag
        $this->assertDatabaseHas('report_dispositions', [
            'report_id' => $report->id,
            'sender_id' => $kasubag->id,
            'receiver_id' => $kabag->id,
        ]);
    }
}

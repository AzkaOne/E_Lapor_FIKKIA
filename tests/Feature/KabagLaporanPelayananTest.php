<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagLaporanPelayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_access_laporan_pelayanan_page(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Pelayanan');
        // Check presence of status filter
        $response->assertSee('name="status"', false);
        $response->assertSee('Semua Status');
        // Check presence of compact sub department filter
        $response->assertSee('name="sub_department_id"', false);
        $response->assertSee('Semua Bidang');
        $response->assertSee('w-full sm:w-36 shrink-0', false);
        // Check presence of single date filter
        $response->assertSee('type="date"', false);
        $response->assertSee('name="date"', false);
        // Ensure no dummy mock rows appear
        $response->assertDontSee('Kerusakan AC di Ruang Kelas 3.2');
        $response->assertDontSee('AzkaOne SIg');
    }

    public function test_empty_state_is_displayed_when_no_reports_exist(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Tidak Ditemukan');
        $response->assertSee('Saat ini belum ada data laporan pelayanan yang masuk ke dalam sistem.');
        // Ensure dummy mock rows and fake pagination are not shown
        $response->assertDontSee('Kerusakan AC di Ruang Kelas 3.2');
        $response->assertDontSee('AzkaOne SIg');
    }

    public function test_empty_state_with_reset_button_when_filtered_out(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        Report::create([
            'ticket_code' => 'TK-TEST-001',
            'title' => 'Laporan Sarpras Riil',
            'description' => 'Deskripsi Sarpras',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);

        // Search with non-matching filter
        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan', [
            'status' => 'selesai',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Tidak Ditemukan');
        $response->assertSee('Tidak ada data laporan yang cocok dengan filter yang Anda tentukan.');
        $response->assertSee('Reset Semua Filter');
        $response->assertDontSee('Laporan Sarpras Riil');
    }

    public function test_filtering_by_status_works(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $reportDiajukan = Report::create([
            'ticket_code' => 'TK-TEST-001',
            'title' => 'Laporan Diajukan Spesifik',
            'description' => 'Deskripsi diajukan',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);

        $reportSelesai = Report::create([
            'ticket_code' => 'TK-TEST-002',
            'title' => 'Laporan Selesai Spesifik',
            'description' => 'Deskripsi selesai',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan', [
            'status' => 'diajukan',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Diajukan Spesifik');
        $response->assertDontSee('Laporan Selesai Spesifik');
    }

    public function test_filtering_by_sub_department_works(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $it = SubDepartment::create(['name' => 'IT & Helpdesk', 'code' => 'IT']);

        $reportSarpras = Report::create([
            'ticket_code' => 'TK-SARPRAS-01',
            'title' => 'Laporan Khusus Sarpras',
            'description' => 'Deskripsi sarpras',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);

        $reportIT = Report::create([
            'ticket_code' => 'TK-IT-01',
            'title' => 'Laporan Khusus IT',
            'description' => 'Deskripsi IT',
            'type' => 'pelayanan',
            'sub_department_id' => $it->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan', [
            'sub_department_id' => $sarpras->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Khusus Sarpras');
        $response->assertDontSee('Laporan Khusus IT');
    }

    public function test_filtering_by_date_works(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $reportToday = new Report([
            'ticket_code' => 'TK-TODAY-01',
            'title' => 'Laporan Hari Ini',
            'description' => 'Deskripsi hari ini',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);
        $reportToday->created_at = now();
        $reportToday->save();

        $reportOld = new Report([
            'ticket_code' => 'TK-OLD-01',
            'title' => 'Laporan Minggu Lalu',
            'description' => 'Deskripsi minggu lalu',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'diajukan',
        ]);
        $reportOld->created_at = now()->subDays(7);
        $reportOld->save();

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan', [
            'date' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Hari Ini');
        $response->assertDontSee('Laporan Minggu Lalu');
    }

    public function test_kabag_laporan_pelayanan_renders_tabs_with_laporan_saya_beside_laporan_pelayanan(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Verifikasi laporan');
        $response->assertSee(route('kabag.my-reports'));
        $response->assertSee(route('kabag.verifikasi.index'));
    }

    public function test_kabag_my_reports_page_uses_laporan_saya_petugas_layout(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-KABAG-MY',
            'title' => 'Pengadaan Laptop Dosen',
            'description' => 'Laptop pengadaan',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'proses',
            'handler_id' => $kabag->id,
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.my-reports'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Saya - Panel KABAG');
        $response->assertSee('LP-KABAG-MY');
        $response->assertSee('Pengadaan Laptop Dosen');
        $response->assertSee(route('kabag.show', $report));
        $response->assertSee('Detail');

        // Tabs
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Verifikasi laporan');

        // No filters for status or date
        $response->assertDontSee('title="Filter Berdasarkan Status Laporan"', false);
        $response->assertDontSee('title="Filter Berdasarkan Tanggal"', false);
    }

    public function test_kabag_laporan_pelayanan_excludes_self_executed_and_completed_reports(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);

        // 1. Incoming report: appears in laporan-pelayanan
        $reportIncoming = Report::create([
            'ticket_code' => 'LP-EXCL-01',
            'title' => 'Laporan Menunggu Disposisi',
            'description' => 'Belum ditangani',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        // 2. Report self-executed by Kabag: in Laporan Saya, must NOT appear in laporan-pelayanan
        $reportSelfExecuted = Report::create([
            'ticket_code' => 'LP-EXCL-02',
            'title' => 'Laporan Dikerjakan Mandiri Kabag',
            'description' => 'Sedang dikerjakan',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'proses',
            'handler_id' => $kabag->id,
        ]);

        // 3. Completed report: in Riwayat, must NOT appear in laporan-pelayanan
        $reportCompleted = Report::create([
            'ticket_code' => 'LP-EXCL-03',
            'title' => 'Laporan Sudah Selesai Tuntas',
            'description' => 'Selesai ditangani',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'selesai',
            'resolution_notes' => 'Tuntas.',
        ]);

        // Access laporan-pelayanan
        $response = $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'));
        $response->assertStatus(200);

        // Incoming report is seen
        $response->assertSee('LP-EXCL-01');

        // Self-executed and completed reports are NOT seen
        $response->assertDontSee('LP-EXCL-02');
        $response->assertDontSee('LP-EXCL-03');

        // Self-executed report appears in Laporan Saya
        $responseSaya = $this->actingAs($kabag)->get(route('kabag.my-reports'));
        $responseSaya->assertSee('LP-EXCL-02');
        $responseSaya->assertDontSee('LP-EXCL-01');
        $responseSaya->assertDontSee('LP-EXCL-03');

        // Completed report appears in Riwayat
        $responseHistory = $this->actingAs($kabag)->get(route('kabag.history'));
        $responseHistory->assertSee('LP-EXCL-03');
    }
}

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
}

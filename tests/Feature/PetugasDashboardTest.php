<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dashboard_renders_matching_kasubag_layout_with_petugas_sidebar(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Budi Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
            'position' => 'Petugas Lapangan',
        ]);

        $report1 = Report::create([
            'ticket_code' => 'LP-PTG-001',
            'title' => 'AC Mati Gedung A',
            'description' => 'AC tidak dingin di ruang 101',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        $report2 = Report::create([
            'ticket_code' => 'LP-PTG-002',
            'title' => 'Pintu Rusak Lab',
            'description' => 'Engsel pintu patah',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'proses',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);

        // Sidebar Petugas items (Dashboard, Laporan Masuk, Riwayat)
        $response->assertSee('Dashboard');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Riwayat');

        // Must NOT see Kasubag/Kabag menus
        $response->assertDontSee('Manajemen Kasubag');
        $response->assertDontSee('Manajemen Petugas');
        $response->assertDontSee('Laporan Whistleblower');

        // Header and Greeting
        $response->assertSee('E-Lapor FIKKIA');
        $response->assertSee('Budi Petugas Sarpras');

        // Top Section: Donut Chart, Stat cards, Notes
        $response->assertSee('Laporan Per Bidang');
        $response->assertSee('Perlu penanganan');
        $response->assertSee('Dalam Penanganan');
        $response->assertSee('Menunggu Verifikasi');
        $response->assertSee('Catatan / Komentar');

        // Table & Side Widgets
        $response->assertSee('Laporan Pelayanan');
        $response->assertSee('Informasi Hari Ini');
        $response->assertSee('Aktivitas Terbaru');

        // Reports and Action buttons
        $response->assertSee('LP-PTG-001');
        $response->assertSee('AC Mati Gedung A');
        $response->assertSee('Mulai Kerjakan');

        $response->assertSee('LP-PTG-002');
        $response->assertSee('Pintu Rusak Lab');
        $response->assertSee('Lanjutkan');
    }
}

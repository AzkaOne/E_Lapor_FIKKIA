<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelaporStatusAndHistoryPenangananTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_pelapor_shows_officer_name_in_penanganan_column_when_assigned(): void
    {
        $dept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $pelapor = User::factory()->create(['role' => 'pelapor']);
        $petugas = User::factory()->create(['role' => 'petugas', 'name' => 'Joko Petugas Lapangan']);

        Report::create([
            'ticket_code' => 'LP-TEST-001',
            'title' => 'Laporan Kerusakan AC',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $dept->id,
            'campus_location' => 'Sobo',
            'description' => 'AC tidak dingin.',
            'status' => 'proses',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.status'));

        $response->assertStatus(200);
        $response->assertSee('Joko Petugas Lapangan');
        $response->assertSee('Petugas Lapangan');
        $response->assertDontSee('Kasubag Sarpras');
    }

    public function test_status_pelapor_shows_menunggu_penugasan_when_unassigned(): void
    {
        $dept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $pelapor = User::factory()->create(['role' => 'pelapor']);

        Report::create([
            'ticket_code' => 'LP-TEST-002',
            'title' => 'Laporan Menunggu Disposisi',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $dept->id,
            'campus_location' => 'Giri',
            'description' => 'Lampu taman padam.',
            'status' => 'diajukan',
            'handler_id' => null,
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.status'));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Penugasan');
    }

    public function test_riwayat_pelapor_shows_officer_name_and_laporan_selesai_column(): void
    {
        $dept = SubDepartment::create(['name' => 'Akademik', 'code' => 'AKADEMIK']);
        $pelapor = User::factory()->create(['role' => 'pelapor']);
        $petugas = User::factory()->create(['role' => 'petugas', 'name' => 'Bambang Teknisi']);

        Report::create([
            'ticket_code' => 'LP-TEST-003',
            'title' => 'Laporan Riwayat Selesai',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $dept->id,
            'campus_location' => 'Sobo',
            'description' => 'Layar proyektor rusak.',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
            'handler_id' => $petugas->id,
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.history'));

        $response->assertStatus(200);
        // Header verification
        $response->assertSee('Laporan Selesai');
        $response->assertSee('Tanggal Laporan');
        $response->assertSee('Penanganan');

        // Body verification
        $response->assertSee('Bambang Teknisi');
        $response->assertSee('Petugas Lapangan');
        $response->assertDontSee('Kasubag Akademik');
    }
}

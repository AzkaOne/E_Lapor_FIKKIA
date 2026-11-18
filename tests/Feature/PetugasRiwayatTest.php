<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasRiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_view_riwayat_petugas_blade_and_sees_own_completed_reports(): void
    {
        $dept = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $pelapor = User::factory()->create([
            'role' => 'pelapor',
            'name' => 'Pelapor Mahasiswa',
        ]);
        $petugas1 = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $dept->id,
            'name' => 'Petugas Satu',
        ]);
        $petugas2 = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $dept->id,
            'name' => 'Petugas Dua',
        ]);

        $report1 = Report::create([
            'user_id' => $pelapor->id,
            'title' => 'Perbaikan Lampu Koridor',
            'description' => 'Lampu koridor mati',
            'type' => 'pelayanan',
            'campus_location' => 'sobo',
            'ticket_code' => 'TIK-001',
            'handler_id' => $petugas1->id,
            'sub_department_id' => $dept->id,
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
            'rating' => 5,
        ]);

        $report2 = Report::create([
            'user_id' => $pelapor->id,
            'title' => 'Pembersihan AC Lab',
            'description' => 'AC berdebu',
            'type' => 'pelayanan',
            'campus_location' => 'giri',
            'ticket_code' => 'TIK-002',
            'handler_id' => $petugas2->id,
            'sub_department_id' => $dept->id,
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($petugas1)->get(route('petugas.history'));

        $response->assertStatus(200);
        $response->assertViewIs('petugas.riwayat_petugas');
        $response->assertSee('Riwayat Laporan Selesai');
        $response->assertSee('Perbaikan Lampu Koridor');
        $response->assertSee('TIK-001');
        $response->assertDontSee('Pembersihan AC Lab');
        $response->assertDontSee('TIK-002');

        // Check detail button links to petugas.show
        $response->assertSee(route('petugas.show', $report1));
    }

    public function test_petugas_riwayat_filtering_and_stats(): void
    {
        $dept = SubDepartment::create(['name' => 'Akademik', 'code' => 'akademik']);
        $pelapor = User::factory()->create([
            'role' => 'pelapor',
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $dept->id,
        ]);

        Report::create([
            'user_id' => $pelapor->id,
            'title' => 'Laporan Terlaksana',
            'description' => 'Selesai tepat waktu',
            'type' => 'pelayanan',
            'campus_location' => 'mojokerto',
            'ticket_code' => 'TIK-TERLAKSANA',
            'handler_id' => $petugas->id,
            'sub_department_id' => $dept->id,
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
            'rating' => 4,
        ]);

        Report::create([
            'user_id' => $pelapor->id,
            'title' => 'Laporan Tertunda',
            'description' => 'Menunggu suku cadang',
            'type' => 'pelayanan',
            'campus_location' => 'sobo',
            'ticket_code' => 'TIK-TERTUNDA',
            'handler_id' => $petugas->id,
            'sub_department_id' => $dept->id,
            'status' => 'selesai',
            'execution_result' => 'tertunda',
        ]);

        // Filter status = terlaksana
        $resFilter = $this->actingAs($petugas)->get(route('petugas.history', ['status' => 'terlaksana']));
        $resFilter->assertStatus(200);
        $resFilter->assertSee('Laporan Terlaksana');
        $resFilter->assertDontSee('Laporan Tertunda');

        // Filter status = tertunda
        $resFilter2 = $this->actingAs($petugas)->get(route('petugas.history', ['status' => 'tertunda']));
        $resFilter2->assertStatus(200);
        $resFilter2->assertSee('Laporan Tertunda');
        $resFilter2->assertDontSee('Laporan Terlaksana');
    }
}

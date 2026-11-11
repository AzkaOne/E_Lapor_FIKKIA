<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasubagLaporanMasukTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_can_view_laporan_masuk_page_with_six_boxes(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
            'email' => 'ksb.sarpras@unair.ac.id',
        ]);

        // Create sample reports for this unit
        Report::create([
            'ticket_code' => 'LP-TEST-1',
            'title' => 'Kerusakan Lampu Kelas 1',
            'description' => 'Lampu mati di ruang kelas',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Sobo',
            'status' => 'didisposisi',
        ]);

        Report::create([
            'ticket_code' => 'LP-TEST-2',
            'title' => 'Perbaikan Pintu Utama',
            'description' => 'Pintu engsel rusak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Mojo',
            'status' => 'proses',
        ]);

        Report::create([
            'ticket_code' => 'LP-TEST-3',
            'title' => 'AC Ruang Kuliah Rusak',
            'description' => 'AC bocor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Giri',
            'status' => 'selesai',
        ]);

        // Check /kasubag/status
        $response = $this->actingAs($kasubag)->get('/kasubag/status');
        $response->assertStatus(200);
        $response->assertSee('Total Laporan');
        $response->assertSee('Laporan Masuk');
        $response->assertSee('Laporan Terlaksana');
        $response->assertSee('Laporan Tertunda');
        $response->assertSee('Laporan Proses');
        $response->assertSee('Laporan Revisi');
        $response->assertSee('LP-TEST-1');
        $response->assertSee('LP-TEST-2');
        $response->assertSee('LP-TEST-3');

        // Check alias /kasubag/laporan-masuk
        $responseAlias = $this->actingAs($kasubag)->get('/kasubag/laporan-masuk');
        $responseAlias->assertStatus(200);
        $responseAlias->assertSee('Total Laporan');
    }

    public function test_kasubag_laporan_masuk_filtering_by_status(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras & Logistik', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        Report::create([
            'ticket_code' => 'LP-INCOMING-1',
            'title' => 'Laporan Masuk Baru',
            'description' => 'Laporan Baru',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Sobo',
            'status' => 'didisposisi',
        ]);

        Report::create([
            'ticket_code' => 'LP-COMPLETED-1',
            'title' => 'Laporan Sudah Selesai',
            'description' => 'Laporan Selesai',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'Mojo',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($kasubag)->get('/kasubag/status?status=masuk');
        $response->assertStatus(200);
        $response->assertSee('LP-INCOMING-1');
        $response->assertDontSee('LP-COMPLETED-1');
    }
}

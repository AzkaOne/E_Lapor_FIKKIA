<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagDetailActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_detail_page_renders_four_action_menus(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);

        $kabag = User::factory()->create(['role' => 'kabag']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'name' => 'Bambang Kasubag Sarpras',
            'sub_department_id' => $sarpras->id,
        ]);
        $petugasSarpras = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Joko Petugas Sarpras',
            'sub_department_id' => $sarpras->id,
        ]);
        $petugasDekanat = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Doni Petugas Dekanat',
            'sub_department_id' => $dekanat->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-TESTVIEW',
            'title' => 'Kerusakan Proyektor Ruang A',
            'description' => 'Proyektor mati total',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($kabag)->get("/kabag/laporan/{$report->id}");

        $response->assertStatus(200);

        // 1. Four action menus exist
        $response->assertSee('Disposisi Ke Kasubag');
        $response->assertSee('Kirim ke Petugas');
        $response->assertSee('Kerjakan Mandiri');
        $response->assertSee('Tolak Laporan');

        // 2. Kasubag modal shows name and sub_department name
        $response->assertSee('Disposisi Laporan Ke Kasubag');
        $response->assertSee('Bambang Kasubag Sarpras');
        $response->assertSee('Bidang: Sarana dan Prasarana');

        // 3. Petugas modal only shows officer matching report's sub_department (Sarpras), NOT Dekanat
        $response->assertSee('Tugaskan Laporan Ke Petugas');
        $response->assertSee('Joko Petugas Sarpras');
        $response->assertDontSee('Doni Petugas Dekanat');

        // 4. Checklist states exist
        $response->assertSee('Lanjutkan Kerjakan Mandiri');
        $response->assertSee('Lanjutkan Tolak Laporan');
    }

    public function test_kabag_detail_shows_informasi_petugas_when_disposed_to_kasubag(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);

        $kabag = User::factory()->create([
            'role' => 'kabag',
            'name' => 'Fuad Raden, S.D., S.Mp',
        ]);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'name' => 'Mas Gatot rojo S.Jmk',
            'identity_number' => '222222222222',
            'email' => 'gatot@unair.ac.id',
            'phone_number' => '082163572544',
            'position' => 'Kepala Sub Bagian',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-DISP-KASUBAG',
            'title' => 'Lampu Koridor Rusak',
            'description' => 'Lampu koridor gedung mati',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'mojo',
            'status' => 'didisposisi',
        ]);

        \App\Models\ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => $kasubag->id,
            'notes' => 'Tolong dicek',
        ]);

        $response = $this->actingAs($kabag)->get("/kabag/laporan/{$report->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Tindakan yang Dapat Dilakukan');
        $response->assertSee('Informasi Petugas');
        $response->assertSee('Mas Gatot rojo S.Jmk');
        $response->assertSee('Bidang Sarana dan Prasarana');
        $response->assertSee('222222222222');
        $response->assertSee('gatot@unair.ac.id');
        $response->assertSee('Kepala Sub Bagian');
        $response->assertSee('082163572544');
    }

    public function test_kabag_detail_shows_informasi_petugas_when_assigned_to_petugas(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);

        $kabag = User::factory()->create(['role' => 'kabag']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Rusdiana, S.Mk., M.A',
            'identity_number' => '333333333333',
            'email' => 'rusdiana@unair.ac.id',
            'phone_number' => '081234762817',
            'position' => 'Petugas',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-PETUGAS',
            'title' => 'Kerusakan AC Lantai 2',
            'description' => 'AC mati total',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'handler_id' => $petugas->id,
            'campus_location' => 'mojo',
            'status' => 'proses',
        ]);

        \App\Models\ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => $petugas->id,
            'notes' => 'Segera perbaiki',
        ]);

        $response = $this->actingAs($kabag)->get("/kabag/laporan/{$report->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Tindakan yang Dapat Dilakukan');
        $response->assertSee('Informasi Petugas');
        $response->assertSee('Rusdiana, S.Mk., M.A');
        $response->assertSee('Bidang Sarana dan Prasarana');
        $response->assertSee('333333333333');
        $response->assertSee('rusdiana@unair.ac.id');
        $response->assertSee('081234762817');
    }

    public function test_kabag_detail_shows_kabag_data_when_rejected(): void
    {
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);

        $kabag = User::factory()->create([
            'role' => 'kabag',
            'name' => 'Fuad Raden, S.D., S.Mp',
            'identity_number' => '111111111111',
            'email' => 'fuad@unair.ac.id',
            'phone_number' => '081234762817',
            'position' => 'Kepala Bagian',
            'sub_department_id' => $dekanat->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-REJECTED',
            'title' => 'Laporan Palsu',
            'description' => 'Test laporan palsu',
            'type' => 'pelayanan',
            'sub_department_id' => $dekanat->id,
            'campus_location' => 'mojo',
            'status' => 'ditolak',
            'rejection_reason' => 'Laporan tidak relevan',
        ]);

        \App\Models\ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => null,
            'notes' => 'Laporan ditolak oleh KABAG: Laporan tidak relevan',
        ]);

        $response = $this->actingAs($kabag)->get("/kabag/laporan/{$report->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Tindakan yang Dapat Dilakukan');
        $response->assertSee('Informasi Petugas');
        $response->assertSee('Fuad Raden, S.D., S.Mp');
        $response->assertSee('Bidang Dekanat');
        $response->assertSee('111111111111');
        $response->assertSee('fuad@unair.ac.id');
        $response->assertSee('Kepala Bagian');
    }

    public function test_kabag_detail_shows_completed_date_when_selesai(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);

        $kabag = User::factory()->create(['role' => 'kabag']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'name' => 'Rusdiana, S.Mk., M.A',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-2026-SELESAI',
            'title' => 'Perbaikan Kran Air',
            'description' => 'Kran bocor',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'handler_id' => $petugas->id,
            'campus_location' => 'mojo',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($kabag)->get("/kabag/laporan/{$report->id}");

        $response->assertStatus(200);
        $response->assertSee('Informasi Petugas');
        $response->assertSee('Rusdiana, S.Mk., M.A');
        $response->assertSee('Diselesaikan pada');
        $response->assertSee($report->updated_at->format('d M Y'));
    }
}

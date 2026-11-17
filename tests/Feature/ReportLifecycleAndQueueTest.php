<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReportLifecycleAndQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_timezone_is_asia_jakarta(): void
    {
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));
        $now = Carbon::now();
        $this->assertEquals('Asia/Jakarta', $now->getTimezone()->getName());
    }

    public function test_petugas_lifecycle_kerjakan_mandiri_to_laporan_saya_then_laporan_masuk_then_riwayat(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-FLOW-PTG-01',
            'title' => 'Lampu Rusak Ruang 102',
            'description' => 'Lampu berkedip',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diterima',
            'handler_id' => $petugas->id,
        ]);

        // 1. Initial State: visible in Laporan Masuk, NOT in Laporan Saya, NOT in Riwayat
        $this->actingAs($petugas)->get(route('petugas.laporan-masuk'))->assertSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.my-reports'))->assertDontSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.history'))->assertDontSee('LP-FLOW-PTG-01');

        // 2. Petugas chooses "Kerjakan Mandiri"
        $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/kerjakan-sendiri")
            ->assertRedirect(route('petugas.my-reports'));

        // Now in Laporan Saya, NOT in default Laporan Masuk, NOT in Riwayat
        $this->actingAs($petugas)->get(route('petugas.my-reports'))->assertSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.laporan-masuk'))->assertDontSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.history'))->assertDontSee('LP-FLOW-PTG-01');

        // 3. Petugas submits execution as terlaksana
        $evidence = UploadedFile::fake()->image('hasil.jpg');
        $this->actingAs($petugas)->post("/petugas/laporan/{$report->id}/selesai", [
            'execution_result' => 'terlaksana',
            'notes' => 'Lampu sudah diperbaiki.',
            'evidence' => $evidence,
        ])->assertRedirect(route('petugas.laporan-masuk'));

        // Moves back to Laporan Masuk, GONE from Laporan Saya, NOT in Riwayat yet
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);
        $this->actingAs($petugas)->get(route('petugas.my-reports'))->assertDontSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.laporan-masuk'))->assertSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.history'))->assertDontSee('LP-FLOW-PTG-01');

        // 4. Kabag verifies report as selesai
        $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Disetujui sudah rapi.',
        ])->assertRedirect(route('kabag.verifikasi.index'));

        $this->flushSession();

        // Now report status is selesai -> moves to Riwayat!
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->actingAs($petugas)->get(route('petugas.my-reports'))->assertDontSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.laporan-masuk'))->assertDontSee('LP-FLOW-PTG-01');
        $this->actingAs($petugas)->get(route('petugas.history'))->assertSee('LP-FLOW-PTG-01');
    }

    public function test_kasubag_lifecycle_kerjakan_mandiri_to_laporan_saya_then_laporan_masuk_then_riwayat(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);

        $report = Report::create([
            'ticket_code' => 'LP-FLOW-KSB-01',
            'title' => 'Pintu Gerbang Rusak',
            'description' => 'Engsel rusak',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'giri',
            'status' => 'didisposisi',
        ]);

        // 1. Initial State: visible in Laporan Masuk, NOT in Laporan Saya
        $this->actingAs($kasubag)->get(route('kasubag.laporan-masuk'))->assertSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.my-reports'))->assertDontSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.history'))->assertDontSee('LP-FLOW-KSB-01');

        // 2. Kasubag chooses "Kerjakan Mandiri"
        $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/kerjakan-sendiri");

        // Now in Laporan Saya
        $this->assertEquals('proses', $report->fresh()->status);
        $this->assertEquals($kasubag->id, $report->fresh()->handler_id);
        $this->actingAs($kasubag)->get(route('kasubag.my-reports'))->assertSee('LP-FLOW-KSB-01');

        // 3. Kasubag submits verification as tertunda
        $this->actingAs($kasubag)->post("/kasubag/laporan/{$report->id}/kirim-verifikasi", [
            'execution_result' => 'tertunda',
            'notes' => 'Menunggu part impor.',
        ])->assertRedirect(route('kasubag.history'));

        // Moves to Riwayat Kasubag, GONE from Laporan Saya, GONE from Laporan Masuk
        $this->assertEquals('verifikasi_kabag', $report->fresh()->status);
        $this->actingAs($kasubag)->get(route('kasubag.my-reports'))->assertDontSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.laporan-masuk'))->assertDontSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.history'))->assertSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.history'))->assertSee('Tertunda');

        // 4. Kabag verifies report as selesai
        $this->actingAs($kabag)->post("/kabag/verifikasi/{$report->id}/approve", [
            'notes' => 'Disetujui ditunda.',
        ]);

        $this->flushSession();

        // Report status is selesai -> visible in Riwayat Kasubag!
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->actingAs($kasubag)->get(route('kasubag.my-reports'))->assertDontSee('LP-FLOW-KSB-01');
        $this->actingAs($kasubag)->get(route('kasubag.history'))->assertSee('LP-FLOW-KSB-01');
    }

    public function test_kabag_lifecycle_kerjakan_mandiri_to_laporan_saya_then_riwayat(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana Prasarana', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $report = Report::create([
            'ticket_code' => 'LP-FLOW-KBG-01',
            'title' => 'Pengadaan Fasilitas Kampus',
            'description' => 'Fasilitas baru',
            'type' => 'pelayanan',
            'sub_department_id' => $sarpras->id,
            'campus_location' => 'sobo',
            'status' => 'diajukan',
        ]);

        // 1. Initial State: Present in Laporan Pelayanan, NOT in Laporan Saya, NOT in Riwayat
        $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'))->assertSee('LP-FLOW-KBG-01');
        $this->actingAs($kabag)->get(route('kabag.my-reports'))->assertDontSee('LP-FLOW-KBG-01');
        $this->actingAs($kabag)->get(route('kabag.history'))->assertDontSee('LP-FLOW-KBG-01');

        // 2. Kabag chooses "Kerjakan Mandiri"
        $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/kerjakan-sendiri");

        // Now in Laporan Saya, and GONE from Laporan Pelayanan!
        $this->assertEquals('proses', $report->fresh()->status);
        $this->assertEquals($kabag->id, $report->fresh()->handler_id);
        $this->actingAs($kabag)->get(route('kabag.my-reports'))->assertSee('LP-FLOW-KBG-01');
        $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'))->assertDontSee('LP-FLOW-KBG-01');

        // 3. Kabag finishes self-execution
        $this->actingAs($kabag)->post("/kabag/laporan/{$report->id}/selesai-mandiri", [
            'execution_result' => 'terlaksana',
            'notes' => 'Diselesaikan langsung oleh Kabag.',
        ])->assertRedirect(route('kabag.history'));

        // Status is selesai -> GONE from Laporan Saya and Laporan Pelayanan, present in Riwayat Kabag!
        $this->assertEquals('selesai', $report->fresh()->status);
        $this->actingAs($kabag)->get(route('kabag.my-reports'))->assertDontSee('LP-FLOW-KBG-01');
        $this->actingAs($kabag)->get(route('kabag.laporan-pelayanan'))->assertDontSee('LP-FLOW-KBG-01');
        $this->actingAs($kabag)->get(route('kabag.history'))->assertSee('LP-FLOW-KBG-01');
    }
}

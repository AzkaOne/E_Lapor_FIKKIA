<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRealtimeAndReceivedNotesTest extends TestCase
{
    use RefreshDatabase;

    protected User $kabag;
    protected User $kasubag;
    protected User $petugas;
    protected SubDepartment $subDept;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subDept = SubDepartment::create([
            'name' => 'Sarana dan Prasarana',
            'code' => 'SARPRAS',
        ]);

        $this->kabag = User::factory()->create([
            'name' => 'Dr. H. Kabag TU',
            'email' => 'kabag@unair.ac.id',
            'role' => 'kabag',
            'sub_department_id' => null,
        ]);

        $this->kasubag = User::factory()->create([
            'name' => 'Kasubag Sarpras',
            'email' => 'kasubag.sarpras@unair.ac.id',
            'role' => 'kasubag',
            'sub_department_id' => $this->subDept->id,
        ]);

        $this->petugas = User::factory()->create([
            'name' => 'Petugas Lapangan',
            'email' => 'petugas@unair.ac.id',
            'role' => 'petugas',
            'sub_department_id' => $this->subDept->id,
        ]);
    }

    public function test_kabag_dashboard_zero_data_displays_zero_and_tidak_ada_data(): void
    {
        $response = $this->actingAs($this->kabag)->get(route('kabag.dashboard'));

        $response->assertStatus(200);

        // Must display 0 in stats and chart total
        $response->assertSee('id="chart-total-count"', false);
        $response->assertSee('>0<', false);
        $response->assertSee('id="stat-perlu-penanganan"', false);
        $response->assertSee('id="stat-dalam-penanganan"', false);
        $response->assertSee('id="stat-menunggu-verifikasi"', false);

        // Must display empty state "Tidak ada data"
        $response->assertSee('Tidak ada data');
        $response->assertSee('Belum ada catatan atau komentar yang diterima.');
        $response->assertSee('Belum ada laporan whistleblower yang masuk.');

        // Must NOT display old fake mockup items or fake fallback numbers
        $response->assertDontSee('Pelecehan petugas');
        $response->assertDontSee('Petugas tidak disiplin');
        $response->assertDontSee('Kerusakan AC di Ruang kelas 3.2');
    }

    public function test_kabag_dashboard_realtime_json_endpoint(): void
    {
        $response = $this->actingAs($this->kabag)->getJson(route('kabag.dashboard', ['realtime' => 1]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'counts' => [
                'total',
                'diajukan',
                'didisposisi',
                'proses',
                'dalam_penanganan',
                'verifikasi',
                'selesai',
                'whistleblowing',
            ],
            'distribution' => [
                'pendidikan',
                'keuangan',
                'sarpras',
                'sdm',
                'whistleblowing',
                'total',
            ],
            'recent_notes',
            'whistleblower_reports',
            'table_html',
        ]);

        $response->assertJson([
            'counts' => [
                'total' => 0,
                'diajukan' => 0,
                'dalam_penanganan' => 0,
                'verifikasi' => 0,
                'whistleblowing' => 0,
            ],
            'distribution' => [
                'total' => 0,
            ],
        ]);
    }

    public function test_received_notes_filtering_across_roles(): void
    {
        $pelapor = User::factory()->create([
            'name' => 'Pelapor Mahasiswa',
            'role' => 'pelapor',
        ]);

        $report1 = Report::create([
            'user_id' => $pelapor->id,
            'sub_department_id' => $this->subDept->id,
            'ticket_code' => 'LAP-101',
            'title' => 'Lampu Koridor Mati',
            'description' => 'Lampu koridor lantai 2 mati total.',
            'type' => 'pelayanan',
            'status' => 'didisposisi',
            'campus_location' => 'B',
        ]);

        // Scenario 1: Kasubag rejects report and inputs comment/reason
        $this->actingAs($this->kasubag)->post(route('kasubag.reject', $report1), [
            'rejection_reason' => 'Bukan ranah kami mohon KABAG alihkan.',
        ]);

        // KABAG must receive this comment in Catatan / Komentar
        $kabagView = $this->actingAs($this->kabag)->get(route('kabag.dashboard'));
        $kabagView->assertStatus(200);
        $kabagView->assertSee('Bukan ranah kami mohon KABAG alihkan.');
        $kabagView->assertSee($this->kasubag->name);

        // KASUBAG must NOT see this comment in their Catatan / Komentar box (they were the sender)
        $kasubagView = $this->actingAs($this->kasubag)->get(route('kasubag.dashboard'));
        $kasubagView->assertStatus(200);
        $kasubagView->assertDontSee('Bukan ranah kami mohon KABAG alihkan.');
        $kasubagView->assertSee('Belum ada catatan atau komentar yang diterima.');

        // Scenario 2: KABAG disposes a report to Kasubag with notes
        $report2 = Report::create([
            'user_id' => $pelapor->id,
            'sub_department_id' => $this->subDept->id,
            'ticket_code' => 'LAP-102',
            'title' => 'Keran Air Bocor',
            'description' => 'Keran toilet bocor.',
            'type' => 'pelayanan',
            'status' => 'diajukan',
            'campus_location' => 'B',
        ]);

        $this->actingAs($this->kabag)->post(route('kabag.dispose', $report2), [
            'target_type' => 'kasubag',
            'receiver_id' => $this->kasubag->id,
            'notes' => 'Tolong segera dicek ya Kasubag Sarpras.',
        ]);

        // KASUBAG must now see this received note from KABAG
        $kasubagView2 = $this->actingAs($this->kasubag)->get(route('kasubag.dashboard'));
        $kasubagView2->assertStatus(200);
        $kasubagView2->assertSee('Tolong segera dicek ya Kasubag Sarpras.');
        $kasubagView2->assertSee($this->kabag->name);

        // KABAG must NOT see their own note in Catatan / Komentar box
        $kabagView2 = $this->actingAs($this->kabag)->get(route('kabag.dashboard'));
        $kabagView2->assertStatus(200);
        $kabagView2->assertDontSee('Tolong segera dicek ya Kasubag Sarpras.');

        // Scenario 3: Kasubag disposes to Petugas with notes
        $this->actingAs($this->kasubag)->post(route('kasubag.dispose', $report2), [
            'receiver_id' => $this->petugas->id,
            'notes' => 'Laksanakan perbaikan keran ini sekarang.',
        ]);

        // PETUGAS must see this received note from Kasubag
        $petugasView = $this->actingAs($this->petugas)->get(route('petugas.dashboard'));
        $petugasView->assertStatus(200);
        $petugasView->assertSee('Laksanakan perbaikan keran ini sekarang.');
        $petugasView->assertSee($this->kasubag->name);

        // KASUBAG must NOT see this note they sent to Petugas
        $kasubagView3 = $this->actingAs($this->kasubag)->get(route('kasubag.dashboard'));
        $kasubagView3->assertStatus(200);
        $kasubagView3->assertDontSee('Laksanakan perbaikan keran ini sekarang.');
    }
}

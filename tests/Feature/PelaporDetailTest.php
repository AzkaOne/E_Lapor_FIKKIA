<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;

class PelaporDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelapor_can_view_own_report_detail(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0001',
            'title' => 'Kerusakan Lampu Lab Gigi',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Sobo',
            'description' => 'Lampu di lab mati total saat praktikum.',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Kerusakan Lampu Lab Gigi');
        $response->assertSee('LP-2026-0001');
        $response->assertSee('Sobo');
        $response->assertSee('Sarpras');
        $response->assertSee('Belum ada komentar');
    }

    public function test_pelapor_cannot_view_other_user_report(): void
    {
        $pelapor1 = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs1@unair.ac.id']);
        $pelapor2 = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs2@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0002',
            'title' => 'Proyektor Ruang 102 Buram',
            'type' => 'pelayanan',
            'user_id' => $pelapor1->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Mojo',
            'description' => 'Proyektor tidak fokus.',
            'status' => 'proses',
        ]);

        $response = $this->actingAs($pelapor2)->get(route('pelapor.show', $report));

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0003',
            'title' => 'Kursi Patah',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Giri',
            'description' => 'Kursi patah di kelas.',
            'status' => 'diajukan',
        ]);

        $response = $this->get(route('pelapor.show', $report));

        $response->assertRedirect('/login');
    }

    public function test_completed_report_shows_rating_section(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0004',
            'title' => 'Pipa Wastafel Bocor',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Sobo',
            'description' => 'Pipa wastafel bocor.',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Beri Penilaian Pelayanan:');
    }

    public function test_newly_submitted_report_timeline_shows_only_dikirim_and_diterima_kabag(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs3@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0005',
            'title' => 'AC Mati',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Sobo',
            'description' => 'AC tidak dingin.',
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Laporan Dikirim');
        $response->assertSee('Diterima Kabag');
        $response->assertDontSee('Diteruskan Ke Kasubag');
        $response->assertDontSee('Ditugaskan ke Petugas');
        $response->assertDontSee('Diverifikasi & Selesai');
        $response->assertSee('https://wa.me/6281234567890');
    }

    public function test_rejected_report_shows_rejection_step_and_reason(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs4@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0006',
            'title' => 'Pintu Rusak',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Giri',
            'description' => 'Pintu tidak bisa ditutup.',
            'status' => 'ditolak',
            'rejection_reason' => 'Bukan wewenang fakultas FIKKIA.',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Laporan Ditolak');
        $response->assertSee('Bukan wewenang fakultas FIKKIA.');
    }

    public function test_sidebar_status_laporan_remains_active_when_viewing_report_detail(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs5@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0007',
            'title' => 'Internet Lambat',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Sobo',
            'description' => 'Koneksi WiFi lemot.',
            'status' => 'proses',
        ]);

        // When viewing from Status Laporan table
        $statusPage = $this->actingAs($pelapor)->get(route('pelapor.status'));
        $statusPage->assertStatus(200);
        $statusPage->assertSee(route('pelapor.show', [$report, 'from' => 'status']));

        // When visiting detail page with from=status
        $response = $this->actingAs($pelapor)->get(route('pelapor.show', [$report, 'from' => 'status']));
        $response->assertStatus(200);

        // Sidebar Status Laporan item must have active class
        $response->assertSeeInOrder([
            'href="' . route('pelapor.status') . '"',
            'bg-[#0F6B60]',
            'Status Laporan',
        ], false);

        // Even without query param for active report, Status Laporan remains active
        $responseWithoutParam = $this->actingAs($pelapor)->get(route('pelapor.show', $report));
        $responseWithoutParam->assertStatus(200);
        $responseWithoutParam->assertSeeInOrder([
            'href="' . route('pelapor.status') . '"',
            'bg-[#0F6B60]',
            'Status Laporan',
        ], false);
    }

    public function test_sidebar_riwayat_laporan_remains_active_when_viewing_history_detail(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs6@unair.ac.id']);
        $subDept = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);

        $report = Report::create([
            'ticket_code' => 'LP-2026-0008',
            'title' => 'Lampu Taman Nyala Terus',
            'type' => 'pelayanan',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'Mojo',
            'description' => 'Lampu taman tidak mati saat siang.',
            'status' => 'selesai',
            'execution_result' => 'terlaksana',
        ]);

        $historyPage = $this->actingAs($pelapor)->get(route('pelapor.history'));
        $historyPage->assertStatus(200);
        $historyPage->assertSee(route('pelapor.show', [$report, 'from' => 'history']));

        $response = $this->actingAs($pelapor)->get(route('pelapor.show', [$report, 'from' => 'history']));
        $response->assertStatus(200);

        // Sidebar Riwayat Laporan item must have active class
        $response->assertSeeInOrder([
            'href="' . route('pelapor.history') . '"',
            'bg-[#0F6B60]',
            'Riwayat Laporan',
        ], false);
    }
}

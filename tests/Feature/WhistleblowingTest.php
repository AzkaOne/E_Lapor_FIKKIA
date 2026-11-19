<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\SubDepartment;
use App\Models\Report;

class WhistleblowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_whistleblowing_page_renders_with_fixed_header_padding_and_fixed_sidebar(): void
    {
        $response = $this->get(route('whistleblowing.create'));
        $response->assertStatus(200);
        $response->assertSee('fixed top-0 left-0 w-full z-50', false);
        $response->assertSee('pt-14 sm:pt-16 lg:pt-[72px]', false);
        $response->assertSee('lg:top-[72px]', false);
    }

    public function test_can_submit_anonymous_whistleblowing_and_receive_ticket(): void
    {
        Storage::fake('public');

        $dept = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEK']);

        $response = $this->post('/whistleblowing', [
            'title' => 'Dugaan Pungli di Kampus Mojo',
            'campus_location' => 'mojo',
            'sub_department_id' => $dept->id,
            'description' => 'Ada pungutan liar parkir.',
            'evidence' => UploadedFile::fake()->create('bukti.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('ticket_code');
        $this->assertDatabaseHas('reports', [
            'title' => 'Dugaan Pungli di Kampus Mojo',
            'type' => 'whistleblowing',
            'user_id' => null,
            'status' => 'diajukan',
        ]);

        $report = Report::where('title', 'Dugaan Pungli di Kampus Mojo')->first();
        $this->assertStringStartsWith('WB-', $report->ticket_code);
        $this->assertCount(1, $report->evidences);
    }

    public function test_can_track_whistleblowing_report_publicly(): void
    {
        $report = Report::create([
            'type' => 'whistleblowing',
            'ticket_code' => 'WB-2026-TEST1',
            'campus_location' => 'sobo',
            'title' => 'Laporan Rahasia 1',
            'description' => 'Deskripsi rahasia',
            'status' => 'proses',
            'resolution_notes' => 'Sedang dalam penyelidikan internal Kabag.',
        ]);

        $response = $this->get('/tracking?ticket_code=WB-2026-TEST1');

        $response->assertStatus(200);
        $response->assertSee('WB-2026-TEST1');
        $response->assertSee('Sedang dalam penyelidikan internal Kabag.');
        $response->assertDontSee('user_id');
    }

    public function test_tracking_non_existent_ticket_shows_error(): void
    {
        $response = $this->get('/tracking?ticket_code=WB-INVALID');

        $response->assertStatus(200);
        $response->assertSee('Kode tiket tidak ditemukan');
    }

    public function test_tracking_renders_comprehensive_information_process_evidences_and_header_auth(): void
    {
        $dept = SubDepartment::create(['name' => 'Sarana & Prasarana', 'code' => 'SARPRAS']);
        $officer = \App\Models\User::factory()->create(['role' => 'petugas', 'name' => 'Budi Santoso']);
        $kabag = \App\Models\User::factory()->create(['role' => 'kabag', 'name' => 'Dr. Bambang']);

        $report = Report::create([
            'type' => 'pelayanan',
            'ticket_code' => 'LP-2026-DETAIL',
            'campus_location' => 'mojo',
            'sub_department_id' => $dept->id,
            'handler_id' => $officer->id,
            'title' => 'Kerusakan AC Ruang Kuliah 301',
            'description' => 'AC di ruang 301 mati total dan bocor air sejak kemarin pagi.',
            'status' => 'proses',
            'resolution_notes' => 'Petugas telah mengganti kompresor AC dan membersihkan filter.',
        ]);

        \App\Models\ReportEvidence::create([
            'report_id' => $report->id,
            'file_path' => 'evidences/pelapor_rusak.jpg',
            'type' => 'initial',
        ]);

        \App\Models\ReportEvidence::create([
            'report_id' => $report->id,
            'file_path' => 'evidences/petugas_selesai.jpg',
            'type' => 'resolution',
        ]);

        \App\Models\ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $kabag->id,
            'receiver_id' => $officer->id,
            'notes' => 'Segera cek dan perbaiki hari ini juga.',
        ]);

        $response = $this->get('/tracking?ticket_code=LP-2026-DETAIL');

        $response->assertStatus(200);

        // Header Auth check
        $response->assertSee('Fakultas Ilmu Kesehatan');
        $response->assertSee('unair_header_logo.png');

        // Information check
        $response->assertSee('LP-2026-DETAIL');
        $response->assertSee('Kerusakan AC Ruang Kuliah 301');
        $response->assertSee('AC di ruang 301 mati total dan bocor air');
        $response->assertSee('Kampus Mojo');
        $response->assertSee('Sarana & Prasarana');
        $response->assertSee('Budi Santoso');

        // Process Timeline check
        $response->assertSee('Alur Penanganan Laporan');
        $response->assertSee('Laporan Diajukan');

        // Evidence check (Pelapor and Petugas)
        $response->assertSee('pelapor_rusak.jpg');
        $response->assertSee('petugas_selesai.jpg');
        $response->assertSee('Bukti Awal dari Pelapor');
        $response->assertSee('Bukti Pengerjaan dari Petugas');

        // Notes and Dispositions check
        $response->assertSee('Petugas telah mengganti kompresor AC');
        $response->assertSee('Segera cek dan perbaiki hari ini juga.');
        $response->assertSee('Dr. Bambang');
    }
}

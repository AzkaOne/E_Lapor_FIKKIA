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
}

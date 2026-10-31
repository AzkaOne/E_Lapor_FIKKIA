<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_sub_department_and_user_with_role(): void
    {
        $subDept = SubDepartment::create([
            'name' => 'Sub Bagian Sarana & Prasarana',
            'code' => 'SARPRAS',
            'description' => 'Menangani fasilitas fisik kampus',
        ]);

        $user = User::create([
            'name' => 'Pak Joko',
            'email' => 'joko@unair.ac.id',
            'password' => bcrypt('password'),
            'role' => 'kasubag',
            'sub_department_id' => $subDept->id,
            'identity_number' => '198501012010121001',
            'position' => 'tendik',
        ]);

        $this->assertDatabaseHas('users', ['email' => $user->email, 'role' => 'kasubag']);
        $this->assertEquals('SARPRAS', $user->subDepartment->code);
    }

    public function test_can_create_report_with_disposition_and_evidence(): void
    {
        $subDept = SubDepartment::create([
            'name' => 'Dekanat',
            'code' => 'DEK',
        ]);

        $pelapor = User::create([
            'name' => 'Mahasiswa A',
            'email' => 'mhs@unair.ac.id',
            'password' => bcrypt('password'),
            'role' => 'pelapor',
            'identity_number' => '0821112233',
            'position' => 'mahasiswa',
        ]);

        $report = Report::create([
            'type' => 'pelayanan',
            'ticket_code' => 'LP-2026-00001',
            'user_id' => $pelapor->id,
            'sub_department_id' => $subDept->id,
            'campus_location' => 'mojo',
            'title' => 'Lampu Koridor Mati',
            'description' => 'Lampu koridor lantai 2 mati.',
            'status' => 'diajukan',
        ]);

        $disposition = ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $pelapor->id,
            'receiver_id' => null,
            'notes' => 'Laporan masuk dari pelapor.',
        ]);

        $evidence = ReportEvidence::create([
            'report_id' => $report->id,
            'uploaded_by_id' => $pelapor->id,
            'file_path' => 'evidences/lampu.jpg',
            'type' => 'initial',
        ]);

        $this->assertDatabaseHas('reports', ['ticket_code' => 'LP-2026-00001']);
        $this->assertCount(1, $report->dispositions);
        $this->assertCount(1, $report->evidences);
        $this->assertEquals('mojo', $report->campus_location);
    }
}

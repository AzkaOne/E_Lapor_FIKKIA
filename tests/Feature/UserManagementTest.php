<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_can_create_petugas_in_their_unit(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $response = $this->actingAs($kasubag)->post('/kasubag/petugas', [
            'name' => 'Joko Petugas',
            'email' => 'joko@unair.ac.id',
            'identity_number' => '199001012015011002',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'joko@unair.ac.id',
            'role' => 'petugas',
            'sub_department_id' => $sarpras->id,
        ]);
    }

    public function test_kabag_can_create_kasubag_and_dekanat_petugas(): void
    {
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEKANAT']);
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag', 'sub_department_id' => $dekanat->id]);

        $response = $this->actingAs($kabag)->post('/kabag/users', [
            'name' => 'Pak Kasubag Sarpras',
            'email' => 'kasubag.sarpras@unair.ac.id',
            'identity_number' => '198501012010011001',
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'kasubag.sarpras@unair.ac.id',
            'role' => 'kasubag',
            'sub_department_id' => $sarpras->id,
        ]);
    }

    public function test_seeders_populate_default_structure_and_accounts(): void
    {
        $this->seed();

        $this->assertDatabaseHas('sub_departments', ['code' => 'SARPRAS']);
        $this->assertDatabaseHas('sub_departments', ['code' => 'SDM_KEUANGAN']);
        $this->assertDatabaseHas('sub_departments', ['code' => 'PENDIDIKAN_KEMAHASISWAAN']);
        $this->assertDatabaseHas('sub_departments', ['code' => 'DEKANAT']);

        $this->assertDatabaseHas('users', ['email' => 'kabag@unair.ac.id', 'role' => 'kabag']);
        $this->assertDatabaseHas('users', ['email' => 'kasubag.sarpras@unair.ac.id', 'role' => 'kasubag']);
        $this->assertDatabaseHas('users', ['email' => 'petugas.sarpras@unair.ac.id', 'role' => 'petugas']);
    }
}
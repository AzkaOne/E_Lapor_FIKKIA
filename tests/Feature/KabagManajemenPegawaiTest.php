<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagManajemenPegawaiTest extends TestCase
{
    use RefreshDatabase;

    protected User $kabag;
    protected SubDepartment $sarprasDept;
    protected SubDepartment $dekanatDept;
    protected SubDepartment $keuanganDept;
    protected SubDepartment $akademikDept;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sarprasDept = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'SARPRAS']);
        $this->dekanatDept = SubDepartment::create(['name' => 'Dekanat dan Departemen', 'code' => 'DEKANAT']);
        $this->keuanganDept = SubDepartment::create(['name' => 'SDM dan Keuangan', 'code' => 'KEUANGAN']);
        $this->akademikDept = SubDepartment::create(['name' => 'Pendidikan dan Kemahasiswaan', 'code' => 'AKADEMIK']);

        $this->kabag = User::factory()->create([
            'name' => 'Dr. H. Kabag TU',
            'email' => 'kabag@unair.ac.id',
            'role' => 'kabag',
            'sub_department_id' => null,
        ]);
    }

    public function test_kabag_can_view_manajemen_pegawai_page_with_five_boxes_and_correct_table_columns(): void
    {
        // Create sample staff
        User::factory()->create([
            'name' => 'Kasubag Sarpras',
            'email' => 'kasubag.sarpras@unair.ac.id',
            'identity_number' => '198001012005011001',
            'role' => 'kasubag',
            'sub_department_id' => $this->sarprasDept->id,
        ]);

        User::factory()->create([
            'name' => 'Petugas Sarpras',
            'email' => 'petugas.sarpras@unair.ac.id',
            'identity_number' => '199001012015011002',
            'role' => 'petugas',
            'sub_department_id' => $this->sarprasDept->id,
        ]);

        $response = $this->actingAs($this->kabag)->get(route('kabag.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kabag.manajemen_pegawai');

        // Assert 5 Stat Boxes
        $response->assertSee('Total Pegawai');
        $response->assertSee('Sarpras');
        $response->assertSee('Dekanat');
        $response->assertSee('Keuangan & SDM');
        $response->assertSee('Akademik');

        // Assert Table Columns
        $response->assertSee('No');
        $response->assertSee('Nama Pegawai');
        $response->assertSee('Email');
        $response->assertSee('NIP/NIK');
        $response->assertSee('Role');
        $response->assertSee('Bidang');
        $response->assertSee('Aksi');

        // Assert Staff Data
        $response->assertSee('Kasubag Sarpras');
        $response->assertSee('kasubag.sarpras@unair.ac.id');
        $response->assertSee('198001012005011001');
        $response->assertSee('KASUBAG');

        $response->assertSee('Petugas Sarpras');
        $response->assertSee('petugas.sarpras@unair.ac.id');
        $response->assertSee('199001012015011002');
        $response->assertSee('Petugas');

        // Assert Action Button Detail & 3-dots Menu (Edit and Hapus)
        $response->assertSee('Detail');
        $response->assertSee('Edit');
        $response->assertSee('Hapus');
    }

    public function test_kabag_can_update_pegawai_data(): void
    {
        $pegawai = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@unair.ac.id',
            'identity_number' => '199101012015011001',
            'role' => 'petugas',
            'sub_department_id' => $this->sarprasDept->id,
        ]);

        $response = $this->actingAs($this->kabag)->put(route('kabag.users.update', $pegawai), [
            'name' => 'Nama Baru, S.T.',
            'email' => 'baru@unair.ac.id',
            'identity_number' => '199101012015011001',
            'role' => 'kasubag',
            'sub_department_id' => $this->keuanganDept->id,
        ]);

        $response->assertRedirect(route('kabag.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $pegawai->id,
            'name' => 'Nama Baru, S.T.',
            'email' => 'baru@unair.ac.id',
            'role' => 'kasubag',
            'sub_department_id' => $this->keuanganDept->id,
        ]);
    }

    public function test_kabag_can_delete_pegawai(): void
    {
        $pegawai = User::factory()->create([
            'name' => 'Pegawai Yang Akan Dihapus',
            'email' => 'hapus@unair.ac.id',
            'identity_number' => '199201012015011001',
            'role' => 'petugas',
            'sub_department_id' => $this->sarprasDept->id,
        ]);

        $response = $this->actingAs($this->kabag)->delete(route('kabag.users.destroy', $pegawai));

        $response->assertRedirect(route('kabag.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $pegawai->id,
        ]);
    }

    public function test_kabag_can_filter_pegawai_by_role_and_sub_department(): void
    {
        User::factory()->create([
            'name' => 'Staf Sarpras',
            'email' => 'staf.sarpras@unair.ac.id',
            'role' => 'kasubag',
            'sub_department_id' => $this->sarprasDept->id,
        ]);

        User::factory()->create([
            'name' => 'Staf Keuangan',
            'email' => 'staf.keuangan@unair.ac.id',
            'role' => 'petugas',
            'sub_department_id' => $this->keuanganDept->id,
        ]);

        // Filter by Sub Department
        $responseDept = $this->actingAs($this->kabag)->get(route('kabag.users.index', [
            'sub_department_id' => $this->sarprasDept->id,
        ]));

        $responseDept->assertStatus(200);
        $responseDept->assertSee('Staf Sarpras');
        $responseDept->assertDontSee('Staf Keuangan');

        // Filter by Role
        $responseRole = $this->actingAs($this->kabag)->get(route('kabag.users.index', [
            'role' => 'petugas',
        ]));

        $responseRole->assertStatus(200);
        $responseRole->assertSee('Staf Keuangan');
        $responseRole->assertDontSee('Staf Sarpras');
    }
}

<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KasubagManajemenPetugasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasubag_can_see_staff_in_their_sub_department(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $akademik = SubDepartment::create(['name' => 'Akademik', 'code' => 'akademik']);

        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugasSarpras = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id, 'name' => 'Petugas Sarpras']);
        $petugasAkademik = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $akademik->id, 'name' => 'Petugas Akademik']);

        $response = $this->actingAs($kasubag)->get(route('kasubag.petugas.index'));
        $response->assertStatus(200);
        $response->assertSee('Petugas Sarpras');
        $response->assertSee($kasubag->name);
        $response->assertDontSee('Petugas Akademik');
    }

    public function test_kasubag_can_create_petugas_locked_to_their_sub_department(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $response = $this->actingAs($kasubag)->post(route('kasubag.petugas.store'), [
            'name' => 'Budi Baru',
            'email' => 'budi.baru@unair.ac.id',
            'identity_number' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $user = User::where('email', 'budi.baru@unair.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertEquals('petugas', $user->role);
        $this->assertEquals($sarpras->id, $user->sub_department_id);
    }

    public function test_kasubag_can_update_petugas_in_same_sub_department(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $response = $this->actingAs($kasubag)->put(route('kasubag.petugas.update', $petugas), [
            'name' => 'Updated Petugas',
            'email' => 'updated.petugas@unair.ac.id',
            'identity_number' => '87654321',
        ]);

        $response->assertRedirect();
        $petugas->refresh();
        $this->assertEquals('Updated Petugas', $petugas->name);
    }

    public function test_kasubag_cannot_edit_or_delete_kasubag_user(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag1 = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $kasubag2 = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);

        $responseUpdate = $this->actingAs($kasubag1)->put(route('kasubag.petugas.update', $kasubag2), [
            'name' => 'Changed Name',
            'email' => 'changed@unair.ac.id',
            'identity_number' => '99999',
        ]);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->actingAs($kasubag1)->delete(route('kasubag.petugas.destroy', $kasubag2));
        $responseDelete->assertStatus(403);
    }

    public function test_kasubag_can_delete_petugas_in_same_sub_department(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $response = $this->actingAs($kasubag)->delete(route('kasubag.petugas.destroy', $petugas));
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $petugas->id]);
    }
}

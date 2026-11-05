<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SubDepartmentSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SubDepartmentSeeder::class);
        $this->seed(UserSeeder::class);
    }

    public function test_user_seeder_creates_official_staff_accounts(): void
    {
        $this->assertDatabaseHas('users', [
            'name' => 'Fuad Raden, S.D., S.Mp',
            'email' => 'fuad@unair.ac.id',
            'identity_number' => '111111111111',
            'phone_number' => '081234762817',
            'role' => 'kabag',
            'position' => 'Kepala Bagian',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Mas Gatot rojo S.Jmk',
            'email' => 'gatot@unair.ac.id',
            'identity_number' => '222222222222',
            'phone_number' => '082163572544',
            'role' => 'kasubag',
            'position' => 'Kepala Sub Bagian',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Rusdiana, S.Mk., M.A',
            'email' => 'rusdiana@unair.ac.id',
            'identity_number' => '333333333333',
            'phone_number' => '081234762817',
            'role' => 'petugas',
            'position' => 'Petugas',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'kabag@unair.ac.id',
            'phone_number' => '081234567801',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'kasubag.sarpras@unair.ac.id',
            'phone_number' => '081234567802',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'petugas.sarpras@unair.ac.id',
            'phone_number' => '081234567806',
        ]);
    }

    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/login-admin');
        $response->assertStatus(200);
        $response->assertSee('Masuk Ke Portal Admin E-Lapor', false);
        $response->assertSee('Halaman khusus untuk Kepala Bagian', false);
    }

    public function test_kabag_can_login_via_nip_and_redirects_to_kabag_dashboard(): void
    {
        $response = $this->post('/login-admin', [
            'login' => '111111111111',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/kabag/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('kabag', auth()->user()->role);
    }

    public function test_kasubag_can_login_via_email_and_redirects_to_kasubag_dashboard(): void
    {
        $response = $this->post('/login-admin', [
            'login' => 'gatot@unair.ac.id',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/kasubag/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('kasubag', auth()->user()->role);
    }

    public function test_petugas_can_login_via_nip_and_redirects_to_petugas_dashboard(): void
    {
        $response = $this->post('/login-admin', [
            'login' => '333333333333',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/petugas/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('petugas', auth()->user()->role);
    }

    public function test_pelapor_cannot_login_through_admin_portal(): void
    {
        User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mhs@unair.ac.id',
            'identity_number' => '0821123456',
            'role' => 'pelapor',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login-admin', [
            'login' => 'mhs@unair.ac.id',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guest_accessing_admin_routes_redirects_to_admin_login(): void
    {
        $response = $this->get('/kabag/dashboard');
        $response->assertRedirect(route('login.admin'));

        $responseKasubag = $this->get('/kasubag/dashboard');
        $responseKasubag->assertRedirect(route('login.admin'));

        $responsePetugas = $this->get('/petugas/dashboard');
        $responsePetugas->assertRedirect(route('login.admin'));
    }
}

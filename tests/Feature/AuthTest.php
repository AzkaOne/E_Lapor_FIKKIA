<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_unair_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Pelapor',
            'email' => 'budi@gmail.com',
            'identity_number' => '082111223344',
            'position' => 'mahasiswa',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'budi@gmail.com']);
    }

    public function test_registration_succeeds_with_unair_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Pelapor',
            'email' => 'budi@unair.ac.id',
            'identity_number' => '082111223344',
            'position' => 'mahasiswa',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/pelapor/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'budi@unair.ac.id',
            'role' => 'pelapor',
            'position' => 'mahasiswa',
        ]);
        $this->assertAuthenticated();
    }

    public function test_login_redirects_user_to_appropriate_role_dashboard(): void
    {
        $kabag = User::create([
            'name' => 'Kabag User',
            'email' => 'kabag@unair.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'kabag',
        ]);

        $response = $this->post('/login', [
            'email' => 'kabag@unair.ac.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/kabag/dashboard');
        $this->assertAuthenticatedAs($kabag);
    }

    public function test_role_middleware_blocks_unauthorized_role(): void
    {
        $pelapor = User::create([
            'name' => 'Pelapor User',
            'email' => 'mhs@unair.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'pelapor',
        ]);

        $response = $this->actingAs($pelapor)->get('/kabag/dashboard');
        $response->assertStatus(403);
    }
}

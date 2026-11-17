<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_profile(): void
    {
        $response = $this->put(route('profile.update'), ['name' => 'New Name']);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_update_basic_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'phone_number' => '08123456789',
            'position' => 'Mahasiswa',
            'avatar' => 'profil_men.webp',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated Name',
            'phone_number' => '08999999999',
            'position' => 'Alumni',
            'avatar' => 'profil_wmn.webp',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('08999999999', $user->phone_number);
        $this->assertEquals('Alumni', $user->position);
        $this->assertEquals('profil_wmn.webp', $user->avatar);
    }

    public function test_user_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'current_password' => 'oldpassword123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_cannot_change_password_with_invalid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_avatar_must_be_valid_option(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'avatar' => 'invalid_avatar.jpg',
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }
}

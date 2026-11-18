<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdatePopupTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_update_shows_popup_for_pelapor(): void
    {
        $user = User::factory()->create(['role' => 'pelapor', 'name' => 'Pelapor A']);

        $response = $this->actingAs($user)->from(route('pelapor.dashboard'))->put(route('profile.update'), [
            'name' => 'Pelapor A Updated',
            'phone_number' => '081234567890',
        ]);

        $response->assertRedirect(route('pelapor.dashboard'));
        $response->assertSessionHas('profile_updated', true);

        // Follow redirect to see the popup in HTML
        $followResponse = $this->actingAs($user)->get(route('pelapor.dashboard'));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Profil Berhasil Diedit');
        $followResponse->assertSee('modal-profile-success');
    }

    public function test_profile_update_shows_popup_for_petugas(): void
    {
        $user = User::factory()->create(['role' => 'petugas', 'name' => 'Petugas B']);

        $response = $this->actingAs($user)->from(route('petugas.dashboard'))->put(route('profile.update'), [
            'name' => 'Petugas B Updated',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $response->assertSessionHas('profile_updated', true);

        $followResponse = $this->actingAs($user)->get(route('petugas.dashboard'));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Profil Berhasil Diedit');
        $followResponse->assertSee('modal-profile-success');
    }

    public function test_profile_update_shows_popup_for_kasubag(): void
    {
        $user = User::factory()->create(['role' => 'kasubag', 'name' => 'Kasubag C']);

        $response = $this->actingAs($user)->from(route('kasubag.dashboard'))->put(route('profile.update'), [
            'name' => 'Kasubag C Updated',
        ]);

        $response->assertRedirect(route('kasubag.dashboard'));
        $response->assertSessionHas('profile_updated', true);

        $followResponse = $this->actingAs($user)->get(route('kasubag.dashboard'));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Profil Berhasil Diedit');
        $followResponse->assertSee('modal-profile-success');
    }

    public function test_profile_update_shows_popup_for_kabag(): void
    {
        $user = User::factory()->create(['role' => 'kabag', 'name' => 'Kabag D']);

        $response = $this->actingAs($user)->from(route('kabag.dashboard'))->put(route('profile.update'), [
            'name' => 'Kabag D Updated',
        ]);

        $response->assertRedirect(route('kabag.dashboard'));
        $response->assertSessionHas('profile_updated', true);

        $followResponse = $this->actingAs($user)->get(route('kabag.dashboard'));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Profil Berhasil Diedit');
        $followResponse->assertSee('modal-profile-success');
    }
}

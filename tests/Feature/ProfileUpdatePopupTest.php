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
        $followResponse->assertDontSee('Laporan Berhasil Terkirim!');
    }

    public function test_pelapor_dashboard_shows_report_success_modal_only_when_ticket_code_present(): void
    {
        $user = User::factory()->create(['role' => 'pelapor', 'name' => 'Pelapor A']);

        // When redirected from report store with ticket_code and report_title
        $response = $this->actingAs($user)
            ->withSession([
                'success' => 'Laporan pelayanan berhasil dikirim dan siap ditinjau oleh KABAG.',
                'ticket_code' => 'LP-TEST-99',
                'report_title' => 'Lampu Rusak di Lab',
            ])
            ->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Berhasil Terkirim!');
        $response->assertSee('LP-TEST-99');
        $response->assertDontSee('Profil Berhasil Diedit');
    }

    public function test_history_blade_files_removed_from_kabag_and_kasubag(): void
    {
        $this->assertFileDoesNotExist(resource_path('views/kabag/history.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/kasubag/history.blade.php'));
        $this->assertFileExists(resource_path('views/kabag/riwayat_kabag.blade.php'));
        $this->assertFileExists(resource_path('views/kasubag/riwayat_kasubag.blade.php'));
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

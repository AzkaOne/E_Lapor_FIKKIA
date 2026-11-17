<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAvatarModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_default_avatar(): void
    {
        $user = User::factory()->create();
        $this->assertEquals('profil_men.webp', $user->avatar);
    }

    public function test_user_can_set_women_avatar(): void
    {
        $user = User::factory()->create(['avatar' => 'profil_wmn.webp']);
        $this->assertEquals('profil_wmn.webp', $user->avatar);
    }
}

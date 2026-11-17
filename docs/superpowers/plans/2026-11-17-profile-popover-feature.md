# Profile Avatar & Popover Feature Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan gambar profil avatar interaktif (default `profil_men.webp` dengan opsi `profil_wmn.webp`) di header Kabag/Staff dan Pelapor, yang saat diklik menampilkan popover detail akun (editable: nama, no telp, jabatan, avatar, password; read-only: nim/nip, email, role, bidang, status) dengan tombol Simpan yang aktif otomatis hanya saat ada perubahan data.

**Architecture:** 
1. Database: Tambah kolom `avatar` pada tabel `users` (default `profil_men.webp`).
2. Backend: `ProfileController` menangani request update profil dan verifikasi ganti password secara aman.
3. UI: Buat komponen reusable `resources/views/komponen/profile_popover.blade.php` dengan Alpine.js untuk popover dropdown, reactive dirty state tracking pada tombol Simpan, serta integrasikan ke `header_kabag.blade.php` dan `header_Pelapor.blade.php`.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS, Alpine.js, PHPUnit/Pest.

**Spec:** Direct user request requirements (Brainstormed and confirmed on 2026-11-17).

## Global Constraints
- Avatar default adalah `profil_men.webp` dan hanya boleh memilih antara `profil_men.webp` dan `profil_wmn.webp` dari `public/assets/`.
- Role Pelapor menampilkan: Nama (editable), NIM (read-only), Email (read-only), No Telp (editable), Jabatan (editable).
- Role Kabag, Kasubag, Petugas menampilkan: Nama (editable), NIP/NIK (read-only), Email (read-only), No Telp (editable), Jabatan (editable), Role (read-only), Bidang (read-only), Status (read-only: Aktif).
- Form ganti password: Password Lama, Password Baru, Konfirmasi Password Baru.
- Tombol Simpan wajib disabled secara default, dan baru enabled jika ada perubahan input (dirty state).
- Semua test harus lulus 100%.

## Review Focus
1. Ganti password gagal jika password lama salah atau tidak diisi saat password baru diinput.
2. Avatar hanya boleh diisi dengan opsi yang valid (`profil_men.webp` atau `profil_wmn.webp`).
3. User tanpa autentikasi (guest) tidak boleh mengakses route update profil.
4. Nilai NIM, Email, Role, Bidang tidak boleh bisa diubah dari payload request profil (server-side protection).
5. Popover menutup dengan benar saat klik di luar popover (`@click.outside`) dan tidak terpotong oleh overflow header/container.

---

### Task 1: Database Migration & User Model for Avatar

**Files:**
- Create: `database/migrations/2026_11_17_064500_add_avatar_to_users_table.php`
- Modify: `app/Models/User.php`
- Test: `tests/Feature/UserAvatarModelTest.php`

**Interfaces:**
- Consumes: `users` table schema
- Produces: `$user->avatar` attribute with default `'profil_men.webp'`

- [ ] **Step 1: Write the failing test**

```php
// tests/Feature/UserAvatarModelTest.php
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
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/UserAvatarModelTest.php`
Expected: FAIL (column `avatar` does not exist)

- [ ] **Step 3: Implement migration and update User model**

Create migration `database/migrations/2026_11_17_064500_add_avatar_to_users_table.php`:
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('avatar')->default('profil_men.webp')->after('position');
});
```
Add `'avatar'` to `$fillable` in `app/Models/User.php`.
Run migration: `php artisan migrate`

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/UserAvatarModelTest.php`
Expected: PASS

---

### Task 2: ProfileController and Update Profile Route

**Files:**
- Create: `app/Http/Controllers/ProfileController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/ProfileUpdateTest.php`

**Interfaces:**
- Consumes: Authenticated session (`auth()->user()`)
- Produces: Route `PUT /profile` named `profile.update`

- [ ] **Step 1: Write the failing tests**

```php
// tests/Feature/ProfileUpdateTest.php
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
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/ProfileUpdateTest.php`
Expected: FAIL (route `profile.update` not defined)

- [ ] **Step 3: Implement ProfileController and register route**

Create `app/Http/Controllers/ProfileController.php`:
- Validate `name`, `phone_number`, `position`, `avatar` (rule: `in:profil_men.webp,profil_wmn.webp`).
- Validate `current_password`, `new_password`, `new_password_confirmation` if `new_password` present.
- Verify `Hash::check($request->current_password, $user->password)` before updating password.
- Update `$user->update([...])` strictly for allowed editable fields.
- Redirect back with success message.

Register route in `routes/web.php`:
```php
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/ProfileUpdateTest.php`
Expected: PASS

---

### Task 3: Profile Popover Blade Component & Header Integration

**Files:**
- Create: `resources/views/komponen/profile_popover.blade.php`
- Modify: `resources/views/komponen/header_kabag.blade.php`
- Modify: `resources/views/komponen/header_Pelapor.blade.php`
- Test: `tests/Feature/ProfilePopoverBladeTest.php`

**Interfaces:**
- Consumes: `auth()->user()`, assets `profil_men.webp`, `profil_wmn.webp`, `route('profile.update')`
- Produces: Interactive Alpine.js popover dropdown in both headers

- [ ] **Step 1: Write the failing tests**

```php
// tests/Feature/ProfilePopoverBladeTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\SubDepartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePopoverBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_pelapor_renders_profile_popover_and_pelapor_fields(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'pelapor',
            'identity_number' => '1234567890',
            'position' => 'Mahasiswa',
            'avatar' => 'profil_men.webp',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('assets/profil_men.webp');
        $response->assertSee('1234567890');
        $response->assertSee('Mahasiswa');
        $response->assertDontSee('Bidang &amp; Role');
    }

    public function test_header_kabag_renders_profile_popover_with_staff_badges(): void
    {
        $subDept = SubDepartment::create(['name' => 'Sarana dan Prasarana']);
        $kabag = User::factory()->create([
            'role' => 'kabag',
            'identity_number' => '198501012010121001',
            'position' => 'Kepala Bagian',
            'sub_department_id' => $subDept->id,
            'avatar' => 'profil_men.webp',
        ]);

        $response = $this->actingAs($kabag)->get(route('kabag.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('assets/profil_men.webp');
        $response->assertSee('198501012010121001');
        $response->assertSee('Kepala Bagian');
        $response->assertSee('Sarana dan Prasarana');
        $response->assertSee('Aktif');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/ProfilePopoverBladeTest.php`
Expected: FAIL (popover and avatar asset references missing)

- [ ] **Step 3: Implement profile_popover.blade.php and update headers**

Create `resources/views/komponen/profile_popover.blade.php`:
- Alpine.js `x-data="profilePopover({ ... initialValues })"`
- Trigger: Avatar image button with border, status indicator, and fallback.
- Popover panel:
  - Header: current avatar preview + radio thumbnail selector between `profil_men.webp` and `profil_wmn.webp`.
  - Body:
    - Nama (input text, bound to Alpine `name`)
    - NIM / NIP (read-only info card/badge with copy/display)
    - Email (read-only input / display)
    - No. Telp (input text, bound to Alpine `phone_number`)
    - Jabatan (input text, bound to Alpine `position`)
    - If staff (`kabag`, `kasubag`, `petugas`): Role badge, Bidang badge, Status badge ("Aktif")
  - Password collapsible/section:
    - Password Lama (input password)
    - Password Baru (input password)
    - Konfirmasi Password Baru (input password)
  - Footer:
    - Button "Simpan": `:disabled="!isDirty"` with disabled styling `opacity-50 cursor-not-allowed` vs enabled active teal styling `bg-[#0F766E] hover:bg-[#0C5E58] shadow-sm`.
- Update `resources/views/komponen/header_kabag.blade.php`: include `komponen.profile_popover`.
- Update `resources/views/komponen/header_Pelapor.blade.php`: include `komponen.profile_popover`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/ProfilePopoverBladeTest.php`
Expected: PASS

---

### Task 4: Full Test Suite Verification

- [ ] **Step 1: Run complete test suite**

Run: `php artisan test`
Expected: PASS all tests (119 existing + all new tests)

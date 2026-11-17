# Kasubag Manajemen Petugas Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memindahkan dan menyelaraskan `resources/views/kasubag/users/index.blade.php` menjadi `resources/views/kasubag/manajemen_petugas.blade.php` dengan desain seperti `manajemen_pegawai.blade.php` tanpa box statistik, membatasi penambahan dan import hanya untuk petugas pada bidang Kasubag, serta mengunci akun Kasubag agar hanya bisa dilihat (read-only / no edit & delete).

**Architecture:**
1. View: Buat `resources/views/kasubag/manajemen_petugas.blade.php` (hapus file lama `users/index.blade.php` dan folder `users`). Hilangkan 5 stat box.
2. Rule Aksi Baris Tabel: Jika `role === 'kasubag'`, hanya tampilkan tombol Detail. Jika `role === 'petugas'`, tampilkan tombol Detail + titik 3 (Edit & Hapus).
3. Rule Tambah & Import: Kunci Role ke `petugas` dan Bidang ke `sub_department_id` milik Kasubag yang sedang login.
4. Controller: Update `Kasubag\UserManagementController` dengan method `index`, `store`, `update`, `destroy`, `import`, dan `downloadTemplate`.
5. Route: Daftarkan route-route kasubag petugas di `routes/web.php`.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS, Alpine.js / Vanilla JS, PHPUnit.

**Spec:** Direct user requirements (2026-11-17).

## Global Constraints
- Tidak ada 5 box statistik di halaman Manajemen Petugas Kasubag.
- Role Kasubag di tabel hanya memiliki tombol Detail (tidak bisa diedit atau dihapus).
- Petugas di tabel memiliki tombol Detail + titik 3 (Edit & Hapus).
- Form tambah dan import hanya untuk role `petugas` di bidang Kasubag yang sedang login.
- File dipindahkan ke `resources/views/kasubag/manajemen_petugas.blade.php`, folder `users` dihapus.
- Semua pengujian lulus 100%.

## Review Focus
1. User Kasubag lain atau Kasubag sendiri di baris tabel tidak menampilkan opsi Edit dan Hapus.
2. Endpoint `update` dan `destroy` Kasubag menolak jika target user bukan role 'petugas' atau bukan di bidang Kasubag yang sama (403 Forbidden).
3. Form tambah petugas otomatis mengikat `sub_department_id` milik Kasubag.
4. Route `kasubag.petugas.index` menampilkan view `kasubag.manajemen_petugas`.
5. Pencarian dan filter nama/email/NIP berfungsi dengan benar.

---

### Task 1: Controller & Route Enhancement for Kasubag User Management

**Files:**
- Modify: `app/Http/Controllers/Kasubag/UserManagementController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/KasubagManajemenPetugasControllerTest.php`

**Interfaces:**
- Routes: `kasubag.petugas.index`, `kasubag.petugas.store`, `kasubag.petugas.update`, `kasubag.petugas.destroy`, `kasubag.petugas.import`, `kasubag.petugas.template`

- [x] **Step 1: Write the failing tests**

```php
// tests/Feature/KasubagManajemenPetugasControllerTest.php
namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response->assertDontSee('Petugas Akademik');
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
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php vendor/bin/phpunit tests/Feature/KasubagManajemenPetugasControllerTest.php`
Expected: FAIL (routes or methods not found)

- [x] **Step 3: Implement controller methods and register routes**

In `Kasubag\UserManagementController`:
- `index`: queries users where `sub_department_id == auth()->user()->sub_department_id` and role in `['kasubag', 'petugas']`. Supports search.
- `store`: force `role = 'petugas'` and `sub_department_id = auth()->user()->sub_department_id`.
- `update`: verify target user has `role === 'petugas'` and `sub_department_id == auth()->user()->sub_department_id`, otherwise abort(403).
- `destroy`: verify target user has `role === 'petugas'` and `sub_department_id == auth()->user()->sub_department_id`, otherwise abort(403).
- `import`: uses `UserImportService`, force/filter import to Kasubag's sub department and role `petugas`.
- `downloadTemplate`: template for petugas.

In `routes/web.php`: register `kasubag.petugas.*` routes.

- [x] **Step 4: Run test to verify it passes**

Run: `php vendor/bin/phpunit tests/Feature/KasubagManajemenPetugasControllerTest.php`
Expected: PASS

---

### Task 2: Create `manajemen_petugas.blade.php` & Remove Old Files

**Files:**
- Create: `resources/views/kasubag/manajemen_petugas.blade.php`
- Delete: `resources/views/kasubag/users/index.blade.php` and directory `resources/views/kasubag/users`
- Test: `tests/Feature/KasubagManajemenPetugasBladeTest.php`

**Interfaces:**
- View `kasubag.manajemen_petugas`:
  - No 5 stat cards.
  - Table shows columns: No, Nama Pegawai, Email, NIP/NIK, Role, Bidang, Aksi.
  - If `role === 'kasubag'`: only Detail button is rendered. No 3-dots dropdown menu.
  - If `role === 'petugas'`: Detail button + 3-dots dropdown (Edit & Hapus) are rendered.
  - Modal Tambah Petugas: Role fixed to `Petugas` (read-only/badge), Bidang fixed to Kasubag's sub department (read-only/badge).
  - Modal Edit Petugas: Role fixed to `Petugas`, Bidang fixed to Kasubag's sub department.

- [x] **Step 1: Write the failing tests**

```php
// tests/Feature/KasubagManajemenPetugasBladeTest.php
namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasubagManajemenPetugasBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_manajemen_petugas_blade_has_no_stat_boxes_and_conditionally_hides_edit_delete(): void
    {
        $dept = SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kasubag = User::factory()->create([
            'role' => 'kasubag',
            'sub_department_id' => $dept->id,
            'name' => 'Kasubag Utama',
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'sub_department_id' => $dept->id,
            'name' => 'Petugas Lapangan',
        ]);

        $response = $this->actingAs($kasubag)->get(route('kasubag.petugas.index'));
        $response->assertStatus(200);

        // Verify NO 5 stat boxes
        $response->assertDontSee('Total Pegawai');
        $response->assertDontSee('Keuangan &amp; SDM', false);

        // Verify kasubag row cannot be edited
        $response->assertSee('Kasubag Utama');
        $response->assertSee('Petugas Lapangan');
        $response->assertSee('Manajemen Tim Petugas');
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php vendor/bin/phpunit tests/Feature/KasubagManajemenPetugasBladeTest.php`
Expected: FAIL

- [x] **Step 3: Create `manajemen_petugas.blade.php` and remove old file/folder**

- Copy layout from `manajemen_pegawai.blade.php`.
- Remove stat cards section.
- Set title: "Manajemen Tim Petugas - Panel KASUBAG E-Lapor FIKKIA UNAIR".
- In table rows:
  ```blade
  @if($user->role === 'kasubag')
      <!-- Only Detail button -->
      <button type="button" onclick="openDetailModal({{ json_encode(...) }})" ...>Detail</button>
  @else
      <!-- Detail + 3-dots dropdown for Edit and Delete -->
      ...
  @endif
  ```
- In Modal Tambah Petugas:
  - Role displayed as fixed badge `Petugas` (`<input type="hidden" name="role" value="petugas">`).
  - Bidang displayed as fixed badge of Kasubag's unit (`<input type="hidden" name="sub_department_id" value="{{ auth()->user()->sub_department_id }}">`).
- In Modal Edit Petugas:
  - Role fixed to `petugas`.
- In Controller: return `view('kasubag.manajemen_petugas', ...)`.
- Delete `resources/views/kasubag/users/index.blade.php` and `resources/views/kasubag/users/`.

- [x] **Step 4: Run test to verify it passes**

Run: `php vendor/bin/phpunit tests/Feature/KasubagManajemenPetugasBladeTest.php`
Expected: PASS

---

### Task 3: Full Test Suite Verification

- [x] **Step 1: Run complete test suite**

Run: `php vendor/bin/phpunit`
Expected: PASS all tests without regressions

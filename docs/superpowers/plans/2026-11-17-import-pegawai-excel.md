# Import Pegawai via Excel / CSV Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan fitur import data pegawai menggunakan file Excel (.xlsx) atau CSV (.csv) di modal Tambah Pegawai pada halaman Manajemen Pegawai Kabag, dengan validasi ketat, password default NIP/NIK jika kosong, penolakan baris dengan role kosong/tidak sesuai, serta popup hasil import yang menampilkan jumlah data tersimpan dan tidak tersimpan.

**Architecture:**
1. Service: `app/Services/UserImportService.php` menangani parsing file CSV dan XLSX (menggunakan native `ZipArchive` & `SimpleXMLElement` untuk XLSX, serta `fgetcsv` untuk CSV) tanpa dependensi library eksternal yang berat.
2. Controller: `UserManagementController@import` memvalidasi file upload, mengeksekusi import via service, dan mengembalikan flash session `import_result`. Method `downloadTemplate` menyediakan file template CSV/Excel.
3. UI: `manajemen_pegawai.blade.php` menambahkan tombol "Import Excel/CSV" di sebelah tombol Batal pada modal tambah pegawai, toggle view untuk area dropzone upload berkas & unduh template, serta modal popup hasil import (`#modal-import-result`).

**Tech Stack:** Laravel 11, PHP 8.5 (ZipArchive, SimpleXMLElement, fgetcsv), Tailwind CSS, Vanilla JS / Alpine.js, PHPUnit.

**Spec:** Direct user requirements (2026-11-17).

## Global Constraints
- Password kosong diisi dengan nomor NIP/NIK pegawai (`bcrypt($nip)`).
- Role yang kosong atau tidak valid (bukan `kasubag` atau `petugas`) ditolak dan tidak tersimpan.
- Bidang/Sub Department harus cocok dengan data sub_departments yang ada di database.
- Email harus berformat valid dan belum pernah terdaftar di tabel `users`.
- Popup hasil menampilkan ringkasan data tersimpan vs tidak tersimpan (misal: "5 data pegawai tersimpan, 2 data pegawai tidak tersimpan").
- Desain baru modal yang telah direvisi oleh user di `manajemen_pegawai.blade.php` tidak boleh rusak atau terhapus.

## Review Focus
1. Baris dengan role kosong atau role selain 'kasubag'/'petugas' wajib gagal dan tidak masuk database.
2. File CSV dengan delimiter titik-koma (`;`) atau koma (`,`) harus terbaca dengan benar (khas Excel Indonesia).
3. Duplikat email dalam satu file batch atau dengan database harus ditolak dengan pesan yang jelas.
4. Nilai NIP/NIK sebagai default password harus di-hash dengan benar dan dapat digunakan untuk login.
5. Modal import result otomatis muncul jika ada session flash `import_result`.

---

### Task 1: UserImportService (CSV & XLSX Parser + Row Validator)

**Files:**
- Create: `app/Services/UserImportService.php`
- Test: `tests/Unit/UserImportServiceTest.php`

**Interfaces:**
- Input: `UploadedFile` or file path
- Output: `array` `['saved_count' => int, 'failed_count' => int, 'failed_rows' => array, 'saved_users' => array]`

- [x] **Step 1: Write the failing tests**

```php
// tests/Unit/UserImportServiceTest.php
namespace Tests\Unit;

use App\Models\SubDepartment;
use App\Models\User;
use App\Services\UserImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserImportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        SubDepartment::create(['name' => 'Akademik', 'code' => 'akademik']);
    }

    public function test_import_csv_with_valid_rows_and_default_nip_password(): void
    {
        $csvContent = "Nama,Email,NIP,Role,Bidang,No_Telp,Jabatan,Password\n" .
                      "Budi Santoso,budi@unair.ac.id,19900101,petugas,Sarana dan Prasarana,08123456789,Staff Sarpras,\n" .
                      "Siti Aminah,siti@unair.ac.id,19920202,kasubag,Akademik,08987654321,Kasubag Akademik,rahasia123\n";

        $file = UploadedFile::fake()->createWithContent('pegawai.csv', $csvContent);

        $service = new UserImportService();
        $result = $service->import($file);

        $this->assertEquals(2, $result['saved_count']);
        $this->assertEquals(0, $result['failed_count']);

        $budi = User::where('email', 'budi@unair.ac.id')->first();
        $this->assertNotNull($budi);
        $this->assertTrue(Hash::check('19900101', $budi->password)); // default password is NIP

        $siti = User::where('email', 'siti@unair.ac.id')->first();
        $this->assertNotNull($siti);
        $this->assertTrue(Hash::check('rahasia123', $siti->password));
    }

    public function test_import_rejects_empty_or_invalid_role(): void
    {
        $csvContent = "Nama,Email,NIP,Role,Bidang\n" .
                      "Valid User,valid@unair.ac.id,11111,petugas,Akademik\n" .
                      "Empty Role,empty@unair.ac.id,22222,,Akademik\n" .
                      "Invalid Role,invalid@unair.ac.id,33333,admin_super,Akademik\n";

        $file = UploadedFile::fake()->createWithContent('pegawai.csv', $csvContent);

        $service = new UserImportService();
        $result = $service->import($file);

        $this->assertEquals(1, $result['saved_count']);
        $this->assertEquals(2, $result['failed_count']);
        $this->assertCount(2, $result['failed_rows']);
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php vendor/bin/phpunit tests/Unit/UserImportServiceTest.php`
Expected: FAIL (Class `App\Services\UserImportService` not found)

- [x] **Step 3: Implement UserImportService**

Create `app/Services/UserImportService.php`:
- Detect format: CSV or XLSX.
- If CSV: read lines with delimiter auto-detection (`,`, `;`, `\t`).
- If XLSX: open with `ZipArchive`, parse `xl/sharedStrings.xml` and `xl/worksheets/sheet1.xml` to extract tabular rows.
- Map headers: `nama`/`name`, `email`, `nip`/`nik`/`identity_number`, `role`, `bidang`/`sub_department`, `no_telp`/`phone_number`, `jabatan`/`position`, `password`.
- Process each row:
  - Check required: `name`, `email`, `nip`, `role`, `bidang`.
  - Validate role in `['kasubag', 'petugas']` (lowercase). Reject if empty or invalid.
  - Validate email uniqueness in DB & in-memory processed emails list.
  - Match sub_department by name (case-insensitive) or code. Reject if not found.
  - If password is empty, set password to NIP (`Hash::make((string)$nip)`).
  - Create user with avatar default `'profil_men.webp'`.
- Return stats.

- [x] **Step 4: Run test to verify it passes**

Run: `php vendor/bin/phpunit tests/Unit/UserImportServiceTest.php`
Expected: PASS

---

### Task 2: Controller Endpoints & Template Download

**Files:**
- Modify: `app/Http/Controllers/Kabag/UserManagementController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/KabagUserImportControllerTest.php`

**Interfaces:**
- Route: `POST /kabag/users/import` -> `kabag.users.import`
- Route: `GET /kabag/users/import-template` -> `kabag.users.template`

- [x] **Step 1: Write the failing tests**

```php
// tests/Feature/KabagUserImportControllerTest.php
namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class KabagUserImportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabag_can_import_pegawai_csv_and_receives_flash_result(): void
    {
        SubDepartment::create(['name' => 'Sarana dan Prasarana', 'code' => 'sarpras']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $csvContent = "Nama,Email,NIP,Role,Bidang\n" .
                      "Doni Pratama,doni@unair.ac.id,19800101,petugas,Sarana dan Prasarana\n" .
                      "Invalid Guy,invalid@unair.ac.id,19800102,direktur,Sarana dan Prasarana\n";

        $file = UploadedFile::fake()->createWithContent('pegawai.csv', $csvContent);

        $response = $this->actingAs($kabag)->post(route('kabag.users.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('import_result');
        $sessionResult = session('import_result');
        $this->assertEquals(1, $sessionResult['saved_count']);
        $this->assertEquals(1, $sessionResult['failed_count']);
    }

    public function test_kabag_can_download_template(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)->get(route('kabag.users.template'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php vendor/bin/phpunit tests/Feature/KabagUserImportControllerTest.php`
Expected: FAIL (route `kabag.users.import` not defined)

- [x] **Step 3: Implement controller methods and routes**

In `app/Http/Controllers/Kabag/UserManagementController.php`:
- Add `import(Request $request, UserImportService $importService)`
- Add `downloadTemplate()` (generates CSV with BOM for Excel compatibility)
In `routes/web.php`:
- Register `kabag.users.import` and `kabag.users.template`.

- [x] **Step 4: Run test to verify it passes**

Run: `php vendor/bin/phpunit tests/Feature/KabagUserImportControllerTest.php`
Expected: PASS

---

### Task 3: UI Integration in `manajemen_pegawai.blade.php`

**Files:**
- Modify: `resources/views/kabag/manajemen_pegawai.blade.php`
- Test: `tests/Feature/KabagUserImportBladeTest.php`

**Interfaces:**
- Modal `#modal-add`:
  - Toggle between Manual Form and Import Upload Form
  - "Import Excel/CSV" button beside "Batal"
  - Drag and drop file zone + file picker
  - "Unduh Contoh Format Template" link pointing to `route('kabag.users.template')`
- Modal `#modal-import-result`:
  - Auto-opens when `session('import_result')` exists
  - Shows `{X} data pegawai tersimpan` and `{Y} data pegawai tidak tersimpan`
  - Expandable detail list of failed items if Y > 0

- [x] **Step 1: Write the failing tests**

```php
// tests/Feature/KabagUserImportBladeTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabagUserImportBladeTest extends TestCase
{
    use RefreshDatabase;

    public function test_blade_renders_import_button_and_template_link(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)->get(route('kabag.manajemen-pegawai'));

        $response->assertStatus(200);
        $response->assertSee('kabag.users.import');
        $response->assertSee('kabag.users.template');
        $response->assertSee('Import');
    }

    public function test_blade_renders_import_result_popup_when_session_present(): void
    {
        $kabag = User::factory()->create(['role' => 'kabag']);
        $response = $this->actingAs($kabag)
            ->withSession([
                'import_result' => [
                    'saved_count' => 5,
                    'failed_count' => 2,
                    'failed_rows' => [
                        ['row' => 3, 'name' => 'Budi', 'reason' => 'Role tidak sesuai'],
                    ],
                ]
            ])
            ->get(route('kabag.manajemen-pegawai'));

        $response->assertStatus(200);
        $response->assertSee('5 data pegawai tersimpan');
        $response->assertSee('2 data pegawai tidak tersimpan');
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php vendor/bin/phpunit tests/Feature/KabagUserImportBladeTest.php`
Expected: FAIL (strings not found in view)

- [x] **Step 3: Update `manajemen_pegawai.blade.php`**

Preserve all existing user modifications in `#modal-add`!
Add:
- Toggle container with tab / view switch: `id="add-mode-manual"` and `id="add-mode-import"`.
- In footer: beside Batal, add button `id="btn-switch-import"` ("Import Excel / CSV") with upload icon.
- In import mode: file upload box (`<input type="file" name="file" accept=".xlsx,.xls,.csv">`), upload illustration, template download link (`route('kabag.users.template')`), and switch back button ("Input Manual").
- Modal `#modal-import-result`:
  - Centered modal displaying counters with styled badges (green for success, rose for failed).
  - List of failed rows with exact line number and reason.

- [x] **Step 4: Run test to verify it passes**

Run: `php vendor/bin/phpunit tests/Feature/KabagUserImportBladeTest.php`
Expected: PASS

---

### Task 4: Full Suite Verification

- [x] **Step 1: Run complete test suite**

Run: `php vendor/bin/phpunit`
Expected: PASS all tests (128 existing + all new import tests)

<?php

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
        $this->assertEquals('Sarana dan Prasarana', $budi->bidang);
        $this->assertEquals('petugas', $budi->role);

        $siti = User::where('email', 'siti@unair.ac.id')->first();
        $this->assertNotNull($siti);
        $this->assertTrue(Hash::check('rahasia123', $siti->password));
        $this->assertEquals('kasubag', $siti->role);
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

    public function test_import_rejects_duplicate_email_and_invalid_bidang(): void
    {
        User::factory()->create(['email' => 'existing@unair.ac.id']);

        $csvContent = "Nama,Email,NIP,Role,Bidang\n" .
                      "Existing User,existing@unair.ac.id,44444,petugas,Akademik\n" .
                      "Invalid Bidang,unknown@unair.ac.id,55555,petugas,Bidang Tidak Ada\n";

        $file = UploadedFile::fake()->createWithContent('pegawai.csv', $csvContent);

        $service = new UserImportService();
        $result = $service->import($file);

        $this->assertEquals(0, $result['saved_count']);
        $this->assertEquals(2, $result['failed_count']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SubDepartment;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dekanat = SubDepartment::where('code', 'DEKANAT')->first();
        $sarpras = SubDepartment::where('code', 'SARPRAS')->first();
        $sdmKeuangan = SubDepartment::where('code', 'SDM_KEUANGAN')->first();
        $kemahasiswaan = SubDepartment::where('code', 'PENDIDIKAN_KEMAHASISWAAN')->first();

        // 1. Kabag
        User::updateOrCreate(['email' => 'kabag@unair.ac.id'], [
            'name' => 'Kepala Bagian Tata Usaha (KABAG)',
            'identity_number' => '197505121998031001',
            'phone_number' => '081234567801',
            'role' => 'kabag',
            'position' => 'tendik',
            'sub_department_id' => $dekanat?->id,
            'password' => Hash::make('password123'),
        ]);

        // 2. Kasubags
        User::updateOrCreate(['email' => 'kasubag.sarpras@unair.ac.id'], [
            'name' => 'KASUBAG Sarana & Prasarana',
            'identity_number' => '198003152005011002',
            'phone_number' => '081234567802',
            'role' => 'kasubag',
            'position' => 'tendik',
            'sub_department_id' => $sarpras?->id,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'kasubag.sdm@unair.ac.id'], [
            'name' => 'KASUBAG SDM & Keuangan',
            'identity_number' => '198207202006042001',
            'phone_number' => '081234567803',
            'role' => 'kasubag',
            'position' => 'tendik',
            'sub_department_id' => $sdmKeuangan?->id,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'kasubag.kemahasiswaan@unair.ac.id'], [
            'name' => 'KASUBAG Pendidikan & Kemahasiswaan',
            'identity_number' => '198411082008121003',
            'phone_number' => '081234567804',
            'role' => 'kasubag',
            'position' => 'tendik',
            'sub_department_id' => $kemahasiswaan?->id,
            'password' => Hash::make('password123'),
        ]);

        // 3. Petugas Lapangan
        User::updateOrCreate(['email' => 'petugas.dekanat@unair.ac.id'], [
            'name' => 'Petugas Layanan Dekanat',
            'identity_number' => '199001012015011001',
            'phone_number' => '081234567805',
            'role' => 'petugas',
            'position' => 'tendik',
            'sub_department_id' => $dekanat?->id,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'petugas.sarpras@unair.ac.id'], [
            'name' => 'Petugas Teknisi Sarpras',
            'identity_number' => '199202022016021002',
            'phone_number' => '081234567806',
            'role' => 'petugas',
            'position' => 'tendik',
            'sub_department_id' => $sarpras?->id,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'petugas.sdm@unair.ac.id'], [
            'name' => 'Petugas Administrasi SDM',
            'identity_number' => '199303032017032003',
            'phone_number' => '081234567807',
            'role' => 'petugas',
            'position' => 'tendik',
            'sub_department_id' => $sdmKeuangan?->id,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'petugas.kemahasiswaan@unair.ac.id'], [
            'name' => 'Petugas Layanan Kemahasiswaan',
            'identity_number' => '199404042018041004',
            'phone_number' => '081234567808',
            'role' => 'petugas',
            'position' => 'tendik',
            'sub_department_id' => $kemahasiswaan?->id,
            'password' => Hash::make('password123'),
        ]);

        // 4. Demo Pelapor (Mahasiswa & Dosen)
        User::updateOrCreate(['email' => 'pelapor@unair.ac.id'], [
            'name' => 'Ahmad Pelapor (Mahasiswa)',
            'phone_number' => '081234567891',
            'identity_number' => '164221054',
            'role' => 'pelapor',
            'position' => 'mahasiswa',
            'sub_department_id' => null,
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'dosen@unair.ac.id'], [
            'name' => 'Dr. Siti Pelapor (Dosen)',
            'phone_number' => '081234567892',
            'identity_number' => '198101012005012001',
            'role' => 'pelapor',
            'position' => 'dosen',
            'sub_department_id' => null,
            'password' => Hash::make('password123'),
        ]);

        // ==========================================
        // 5. Akun Pengelola Resmi (Kabag, Kasubag, Petugas)
        // ==========================================

        // KABAG: Fuad Raden, S.D., S.Mp
        User::updateOrCreate(['email' => 'fuad@unair.ac.id'], [
            'name' => 'Fuad Raden, S.D., S.Mp',
            'identity_number' => '111111111111',
            'phone_number' => '081234762817',
            'role' => 'kabag',
            'position' => 'Kepala Bagian',
            'sub_department_id' => $dekanat?->id,
            'password' => Hash::make('12345678'),
        ]);

        // KASUBAG: Mas Gatot rojo S.Jmk (Bidang Sarana Prasarana)
        User::updateOrCreate(['email' => 'gatot@unair.ac.id'], [
            'name' => 'Mas Gatot rojo S.Jmk',
            'identity_number' => '222222222222',
            'phone_number' => '082163572544',
            'role' => 'kasubag',
            'position' => 'Kepala Sub Bagian',
            'sub_department_id' => $sarpras?->id,
            'password' => Hash::make('12345678'),
        ]);

        // PETUGAS: Rusdiana, S.Mk., M.A (Bidang Sarana Prasarana)
        User::updateOrCreate(['email' => 'rusdiana@unair.ac.id'], [
            'name' => 'Rusdiana, S.Mk., M.A',
            'identity_number' => '333333333333',
            'phone_number' => '081234762817',
            'role' => 'petugas',
            'position' => 'Petugas',
            'sub_department_id' => $sarpras?->id,
            'password' => Hash::make('12345678'),
        ]);
    }
}

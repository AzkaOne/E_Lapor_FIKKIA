<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubDepartment;

class SubDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Dekanat dan Departemen',
                'code' => 'DEKANAT',
                'description' => 'Pimpinan Fakultas, Dekan, Wakil Dekan, dan Departemen Akademik.',
            ],
            [
                'name' => 'Sarana dan Prasarana',
                'code' => 'SARPRAS',
                'description' => 'Pengelolaan fasilitas fisik, gedung, kelistrikan, air, AC, dan ruang kuliah.',
            ],
            [
                'name' => 'SDM dan Keuangan',
                'code' => 'SDM_KEUANGAN',
                'description' => 'Layanan kepegawaian tenaga pendidik/kependidikan serta administrasi keuangan.',
            ],
            [
                'name' => 'Pendidikan dan Kemahasiswaan',
                'code' => 'PENDIDIKAN_KEMAHASISWAAN',
                'description' => 'Layanan akademik, kemahasiswaan, beasiswa, dan organisasi mahasiswa (Ormawa).',
            ],
        ];

        foreach ($departments as $dept) {
            SubDepartment::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}

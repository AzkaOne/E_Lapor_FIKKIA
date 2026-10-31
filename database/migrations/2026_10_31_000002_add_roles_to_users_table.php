<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pelapor')->after('email'); // pelapor, kabag, kasubag, petugas
            $table->string('identity_number')->nullable()->after('role'); // NIM / NIP
            $table->string('position')->nullable()->after('identity_number'); // mahasiswa, dosen, tendik
            $table->foreignId('sub_department_id')->nullable()->after('position')->constrained('sub_departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sub_department_id']);
            $table->dropColumn(['role', 'identity_number', 'position', 'sub_department_id']);
        });
    }
};

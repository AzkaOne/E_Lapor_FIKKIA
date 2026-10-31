<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('pelayanan'); // pelayanan, whistleblowing
            $table->string('ticket_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sub_department_id')->nullable()->constrained('sub_departments')->nullOnDelete();
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('campus_location'); // mojo, sobo, giri
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('diajukan'); // diajukan, didisposisi, diterima, proses, verifikasi_kabag, selesai, ditolak
            $table->string('execution_result')->nullable(); // terlaksana, tertunda
            $table->unsignedTinyInteger('rating')->nullable(); // 1 - 5
            $table->text('rejection_reason')->nullable();
            $table->text('postponed_reason')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

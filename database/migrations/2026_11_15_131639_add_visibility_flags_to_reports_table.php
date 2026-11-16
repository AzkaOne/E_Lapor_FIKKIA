<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->boolean('show_evidence_to_pelapor')->default(true)->after('resolution_notes');
            $table->boolean('show_notes_to_pelapor')->default(true)->after('show_evidence_to_pelapor');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['show_evidence_to_pelapor', 'show_notes_to_pelapor']);
        });
    }
};

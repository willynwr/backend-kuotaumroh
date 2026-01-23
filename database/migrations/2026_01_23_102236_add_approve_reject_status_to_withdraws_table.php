<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kolom status sudah ada sebagai VARCHAR dari migration sebelumnya
        // Migration ini hanya untuk dokumentasi bahwa status bisa menerima nilai: pending, approve, reject
        // Tidak perlu perubahan struktur karena VARCHAR sudah support semua nilai
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada perubahan yang perlu di-revert
    }
};

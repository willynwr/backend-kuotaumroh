<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Hapus kolom jenis_agent jika ada
            if (Schema::hasColumn('agents', 'jenis_agent')) {
                $table->dropColumn('jenis_agent');
            }
            
            // Tambah foreign key ke affiliates (nullable)
            $table->foreignId('affiliate_id')->nullable()->after('id')->constrained('affiliates')->onDelete('cascade');
            
            // Tambah foreign key ke freelances (nullable)
            $table->foreignId('freelance_id')->nullable()->after('affiliate_id')->constrained('freelances')->onDelete('cascade');
            
            // Tambah kategori_agent (Referral/Host)
            $table->enum('kategori_agent', ['Referral', 'Host'])->after('freelance_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Drop foreign keys dan kolom
            $table->dropForeign(['affiliate_id']);
            $table->dropForeign(['freelance_id']);
            $table->dropColumn(['affiliate_id', 'freelance_id', 'kategori_agent']);
            
            // Restore jenis_agent jika perlu
            $table->enum('jenis_agent', ['travel agent', 'agent', 'freelance'])->after('id');
        });
    }
};

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
        // Update existing data first
        DB::table('pembayaran')
            ->where('status_pembayaran', 'selesai')
            ->update(['status_pembayaran' => 'berhasil_temp']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'menunggu pembayaran')
            ->update(['status_pembayaran' => 'proses_temp']);

        // Alter column to new enum values
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('berhasil', 'proses', 'gagal') DEFAULT 'proses'");

        // Update temporary values to final values
        DB::table('pembayaran')
            ->where('status_pembayaran', 'berhasil_temp')
            ->update(['status_pembayaran' => 'berhasil']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'proses_temp')
            ->update(['status_pembayaran' => 'proses']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing data first
        DB::table('pembayaran')
            ->where('status_pembayaran', 'berhasil')
            ->update(['status_pembayaran' => 'selesai_temp']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'proses')
            ->update(['status_pembayaran' => 'menunggu_temp']);

        // Alter column back to old enum values
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('selesai', 'menunggu pembayaran', 'gagal') DEFAULT 'menunggu pembayaran'");

        // Update temporary values to final values
        DB::table('pembayaran')
            ->where('status_pembayaran', 'selesai_temp')
            ->update(['status_pembayaran' => 'selesai']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'menunggu_temp')
            ->update(['status_pembayaran' => 'menunggu pembayaran']);
    }
};

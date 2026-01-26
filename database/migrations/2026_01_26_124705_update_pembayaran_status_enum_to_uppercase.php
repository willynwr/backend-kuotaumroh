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
        // Update existing data to temporary values
        DB::table('pembayaran')
            ->where('status_pembayaran', 'berhasil')
            ->update(['status_pembayaran' => 'temp_success']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'proses')
            ->update(['status_pembayaran' => 'temp_waiting']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'gagal')
            ->update(['status_pembayaran' => 'temp_failed']);

        // Alter column to new uppercase enum values
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('WAITING', 'VERIFY', 'SUCCESS', 'FAILED', 'EXPIRED') DEFAULT 'WAITING'");

        // Update temporary values to final uppercase values
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_success')
            ->update(['status_pembayaran' => 'SUCCESS']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_waiting')
            ->update(['status_pembayaran' => 'WAITING']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_failed')
            ->update(['status_pembayaran' => 'FAILED']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing data to temporary values
        DB::table('pembayaran')
            ->where('status_pembayaran', 'SUCCESS')
            ->update(['status_pembayaran' => 'temp_berhasil']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'WAITING')
            ->update(['status_pembayaran' => 'temp_proses']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'VERIFY')
            ->update(['status_pembayaran' => 'temp_proses']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'FAILED')
            ->update(['status_pembayaran' => 'temp_gagal']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'EXPIRED')
            ->update(['status_pembayaran' => 'temp_gagal']);

        // Revert to lowercase enum
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('berhasil', 'proses', 'gagal') DEFAULT 'proses'");

        // Update temporary values back to lowercase
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_berhasil')
            ->update(['status_pembayaran' => 'berhasil']);
        
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_proses')
            ->update(['status_pembayaran' => 'proses']);
            
        DB::table('pembayaran')
            ->where('status_pembayaran', 'temp_gagal')
            ->update(['status_pembayaran' => 'gagal']);
    }
};

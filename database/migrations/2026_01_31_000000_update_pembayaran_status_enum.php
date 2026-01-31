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
        // Normalize existing data to match new ENUM values
        DB::table('pembayaran')->where('status_pembayaran', 'SUKSES')->update(['status_pembayaran' => 'SUCCESS']);
        DB::table('pembayaran')->where('status_pembayaran', 'berhasil')->update(['status_pembayaran' => 'SUCCESS']);
        DB::table('pembayaran')->where('status_pembayaran', 'selesai')->update(['status_pembayaran' => 'SUCCESS']);
        
        DB::table('pembayaran')->where('status_pembayaran', 'menunggu pembayaran')->update(['status_pembayaran' => 'WAITING']);
        DB::table('pembayaran')->where('status_pembayaran', 'pending')->update(['status_pembayaran' => 'WAITING']);
        DB::table('pembayaran')->where('status_pembayaran', 'proses')->update(['status_pembayaran' => 'WAITING']);
        
        DB::table('pembayaran')->where('status_pembayaran', 'gagal')->update(['status_pembayaran' => 'FAILED']);
        
        // Update status_pembayaran ENUM to match server
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('WAITING', 'VERIFY', 'SUCCESS', 'FAILED', 'EXPIRED') DEFAULT 'WAITING'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: revert logic if needed
    }
};

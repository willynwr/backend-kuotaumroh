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
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop and recreate pesanan table to match server structure
        Schema::dropIfExists('pesanan');
        
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('kategori_channel', 20);
            $table->unsignedBigInteger('channel_id');
            $table->string('batch_id');
            $table->unsignedBigInteger('produk_id');
            $table->string('nama_batch');
            $table->string('msisdn');
            $table->string('nama_paket');
            $table->string('tipe_paket');
            $table->integer('masa_aktif')->default(0);
            $table->bigInteger('total_kuota')->default(0);
            $table->bigInteger('kuota_utama')->default(0);
            $table->bigInteger('kuota_bonus')->default(0);
            $table->boolean('telp')->default(false);
            $table->boolean('sms')->default(false);
            $table->bigInteger('harga_modal')->default(0);
            $table->bigInteger('harga_jual')->default(0);
            $table->bigInteger('profit')->default(0);
            $table->dateTime('jadwal_aktivasi');
            $table->enum('status_aktivasi', ['berhasil', 'proses', 'gagal'])->default('proses');
            $table->timestamps();
            
            // Add indexes
            $table->index('channel_id', 'pesanan_agent_id_foreign');
            $table->index('produk_id', 'pesanan_produk_id_foreign');
        });
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('pesanan');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

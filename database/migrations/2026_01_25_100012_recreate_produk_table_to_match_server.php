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
        
        // Drop all tables that reference produk first
        Schema::dropIfExists('margin');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('pembayaran');
        
        // Drop the existing produk table and recreate it with proper structure
        Schema::dropIfExists('produk');
        
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('provider', 20);
            $table->string('nama_paket', 100);
            $table->string('tipe_paket', 100);
            $table->integer('masa_aktif')->comment('dalam hari');
            $table->string('total_kuota', 20)->nullable()->comment('dalam MB/GB');
            $table->string('kuota_utama', 20)->nullable()->comment('dalam MB/GB');
            $table->string('kuota_bonus', 20)->nullable()->comment('dalam MB/GB');
            $table->string('telp', 20)->nullable()->comment('menit');
            $table->string('sms', 20)->nullable()->comment('jumlah sms');
            $table->integer('harga_modal')->default(0);
            $table->integer('harga_app')->default(0);
            $table->integer('persentase_marginstar')->default(0);
            $table->integer('marginstar')->default(0);
            $table->integer('poin')->default(0);
            $table->string('source_name', 20);
            $table->string('promo', 20)->nullable();
            $table->timestamps();
        });
        
        // Recreate margin table that was dropped
        Schema::create('margin', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('agent_id', 10)->nullable();
            $table->string('affiliate_id', 10)->nullable();
            $table->string('freelance_id', 10)->nullable();
            $table->string('produk_id', 20)->nullable();
            $table->integer('harga_eup');
            $table->decimal('persentase_margin_star', 5, 2)->default(0.00);
            $table->integer('margin_star')->default(0);
            $table->integer('margin_total')->default(0);
            $table->integer('fee_travel')->default(0);
            $table->decimal('persentase_fee_travel', 5, 2)->default(0.00);
            $table->decimal('persentase_fee_affiliate', 5, 2)->default(0.00);
            $table->integer('fee_affiliate')->default(0);
            $table->decimal('persentase_fee_host', 5, 2)->default(0.00);
            $table->integer('fee_host')->default(0);
            $table->integer('harga_tp_travel')->default(0);
            $table->integer('harga_tp_host')->default(0);
            $table->integer('poin')->default(0);
            $table->integer('profit')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('agent_id', 'margin_agent_id_index');
            $table->index('affiliate_id', 'margin_affiliate_id_index');
            $table->index('freelance_id', 'margin_freelance_id_index');
            $table->index('produk_id', 'margin_produk_id_index');
        });
        
        // Recreate pesanan table with varchar produk_id
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('kategori_channel', 20);
            $table->unsignedBigInteger('channel_id');
            $table->string('batch_id');
            $table->string('produk_id', 20);
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
        
        // Recreate pembayaran table with varchar produk_id
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('batch_id');
            $table->unsignedBigInteger('agent_id');
            $table->string('produk_id', 20);
            $table->string('msisdn');
            $table->string('nama_paket');
            $table->string('tipe_paket');
            $table->integer('harga_modal')->default(0);
            $table->integer('harga_jual')->default(0);
            $table->integer('profit')->default(0);
            $table->dateTime('tanggal_pembayaran');
            $table->integer('total_pembayaran')->default(0);
            $table->enum('status_pembayaran', ['berhasil', 'proses', 'gagal'])->default('proses');
            $table->timestamps();
            
            // Add indexes
            $table->index('agent_id', 'pembayaran_agent_id_foreign');
            $table->index('produk_id', 'pembayaran_produk_id_foreign');
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
        Schema::dropIfExists('produk');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

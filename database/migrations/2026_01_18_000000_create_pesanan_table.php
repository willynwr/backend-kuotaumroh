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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->string('nama_batch');
            $table->string('msisdn');
            $table->string('nama_paket');
            $table->string('tipe_paket');
            $table->integer('masa_aktif');
            $table->bigInteger('total_kuota');
            $table->bigInteger('kuota_utama');
            $table->bigInteger('kuota_bonus');
            $table->boolean('telp')->default(false);
            $table->boolean('sms')->default(false);
            $table->bigInteger('harga_modal');
            $table->bigInteger('harga_jual');
            $table->bigInteger('profit');
            $table->dateTime('jadwal_aktivasi');
            $table->enum('status_aktivasi', ['berhasil', 'proses', 'gagal'])->default('proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

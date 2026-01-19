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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->string('nama_batch');
            $table->bigInteger('sub_total');
            $table->bigInteger('biaya_platform')->default(0);
            $table->bigInteger('total_pembayaran');
            $table->bigInteger('profit');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('va')->nullable();
            $table->enum('status_pembayaran', ['selesai', 'menunggu pembayaran', 'gagal'])->default('menunggu pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};

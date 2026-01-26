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
        Schema::table('pembayaran', function (Blueprint $table) {
            // External payment ID dari tokodigi (contoh: 176940255243000)
            $table->string('external_payment_id')->nullable()->after('id')->index();
            
            // QRIS fields
            $table->text('qris_string')->nullable()->after('metode_pembayaran');
            $table->string('qris_nmid')->nullable()->after('qris_string');
            $table->string('qris_rrn')->nullable()->after('qris_nmid');
            
            // Detail pesanan (JSON)
            $table->text('detail_pesanan')->nullable()->after('status_pembayaran');
            
            // Nama batch
            $table->string('batch_name')->nullable()->after('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'external_payment_id',
                'qris_string',
                'qris_nmid',
                'qris_rrn',
                'detail_pesanan',
                'batch_name'
            ]);
        });
    }
};

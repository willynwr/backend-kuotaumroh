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
        Schema::create('produk_affiliate', function (Blueprint $table) {
            $table->string('metode_pembelian', 20);
            $table->string('produk_id', 20);
            $table->string('affiliate_id', 100);
            $table->integer('fee_host')->default(0);
            $table->integer('persentase_fee_host')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('metode_pembelian', 'produk_affiliate_metode_pembelian_IDX');
            $table->index('produk_id', 'produk_affiliate_produk_id_IDX');
            $table->index('affiliate_id', 'produk_affiliate_affiliate_id_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_affiliate');
    }
};

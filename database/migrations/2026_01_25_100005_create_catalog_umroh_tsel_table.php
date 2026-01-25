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
        Schema::create('CATALOG_UMROH_TSEL', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('type', 100)->nullable();
            $table->string('sub_type', 100)->nullable();
            $table->string('name', 150)->nullable();
            $table->integer('days')->default(0);
            $table->string('quota', 50)->nullable();
            $table->string('telp', 50)->nullable();
            $table->string('sms', 50)->nullable();
            $table->string('bonus', 50)->nullable();
            $table->integer('price_modal')->nullable();
            $table->integer('price_app')->nullable();
            $table->integer('price_customer')->nullable();
            $table->integer('price_bulk')->nullable();
            $table->integer('price_self')->nullable();
            $table->integer('fee_affiliate')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('product_id', 50)->nullable();
            $table->string('menu_id', 50)->nullable();
            $table->integer('source_digipos')->default(0);
            $table->string('source_name', 50)->nullable();
            $table->string('promo', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CATALOG_UMROH_TSEL');
    }
};

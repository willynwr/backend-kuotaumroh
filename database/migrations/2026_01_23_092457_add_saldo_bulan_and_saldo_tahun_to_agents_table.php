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
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('saldo_bulan')->default(0)->after('saldo')->comment('Profit bulan ini, reset setiap bulan');
            $table->integer('saldo_tahun')->default(0)->after('saldo_bulan')->comment('Akumulasi profit tahun ini, reset setiap tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['saldo_bulan', 'saldo_tahun']);
        });
    }
};

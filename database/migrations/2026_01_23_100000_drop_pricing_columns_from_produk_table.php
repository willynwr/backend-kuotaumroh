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
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn([
                'harga_eup',
                'persentase_margin_star',
                'margin_star',
                'margin_total',
                'fee_travel',
                'persentase_fee_travel',
                'persentase_fee_affiliate',
                'fee_affiliate',
                'persentase_fee_host',
                'fee_host',
                'harga_tp_travel',
                'harga_tp_host',
                'poin',
                'profit',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->integer('harga_eup')->after('harga_modal');
            $table->decimal('persentase_margin_star', 5, 2)->default(0)->after('harga_eup');
            $table->integer('margin_star')->default(0)->after('persentase_margin_star');
            $table->integer('margin_total')->default(0)->after('margin_star');
            $table->integer('fee_travel')->default(0)->after('margin_total');
            $table->decimal('persentase_fee_travel', 5, 2)->default(0)->after('fee_travel');
            $table->decimal('persentase_fee_affiliate', 5, 2)->default(0)->after('persentase_fee_travel');
            $table->integer('fee_affiliate')->default(0)->after('persentase_fee_affiliate');
            $table->decimal('persentase_fee_host', 5, 2)->default(0)->after('fee_affiliate');
            $table->integer('fee_host')->default(0)->after('persentase_fee_host');
            $table->integer('harga_tp_travel')->default(0)->after('fee_host');
            $table->integer('harga_tp_host')->default(0)->after('harga_tp_travel');
            $table->integer('poin')->default(0)->after('harga_tp_host');
            $table->integer('profit')->default(0)->after('poin');
        });
    }
};

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
        Schema::table('affiliate', function (Blueprint $table) {
            $table->string('ktp')->nullable()->after('alamat_lengkap');
        });

        Schema::table('freelance', function (Blueprint $table) {
            $table->string('ktp')->nullable()->after('alamat_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate', function (Blueprint $table) {
            $table->dropColumn('ktp');
        });

        Schema::table('freelance', function (Blueprint $table) {
            $table->dropColumn('ktp');
        });
    }
};

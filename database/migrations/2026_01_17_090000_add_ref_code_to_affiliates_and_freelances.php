<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('ref_code')->nullable()->unique();
        });

        Schema::table('freelances', function (Blueprint $table) {
            $table->string('ref_code')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropUnique('affiliates_ref_code_unique');
            $table->dropColumn('ref_code');
        });

        Schema::table('freelances', function (Blueprint $table) {
            $table->dropUnique('freelances_ref_code_unique');
            $table->dropColumn('ref_code');
        });
    }
};

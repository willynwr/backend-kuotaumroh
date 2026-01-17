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
            $table->text('link_gmaps')->nullable()->after('alamat_lengkap');
            $table->decimal('long', 10, 7)->nullable()->after('link_gmaps');
            $table->decimal('lat', 10, 7)->nullable()->after('long');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['link_gmaps', 'long', 'lat']);
        });
    }
};

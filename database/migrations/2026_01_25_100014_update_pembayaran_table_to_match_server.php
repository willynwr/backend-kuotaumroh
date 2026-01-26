<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Modify pembayaran table to match server structure
        Schema::table('pembayaran', function (Blueprint $table) {
            // Add agent_id and produk_id columns
            if (!Schema::hasColumn('pembayaran', 'agent_id')) {
                $table->unsignedBigInteger('agent_id')->after('batch_id');
            }
            if (!Schema::hasColumn('pembayaran', 'produk_id')) {
                $table->unsignedBigInteger('produk_id')->after('agent_id');
            }
        });
        
        // Add indexes only if they don't exist
        $existingIndexes = collect(DB::select("SHOW INDEX FROM pembayaran"))->pluck('Key_name')->unique()->toArray();
        
        if (!in_array('pembayaran_agent_id_foreign', $existingIndexes)) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->index('agent_id', 'pembayaran_agent_id_foreign');
            });
        }
        
        if (!in_array('pembayaran_produk_id_foreign', $existingIndexes)) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->index('produk_id', 'pembayaran_produk_id_foreign');
            });
        }
        
        // Update enum values for status_pembayaran
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('berhasil', 'proses', 'gagal') DEFAULT 'proses'");
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropIndex('pembayaran_agent_id_foreign');
            $table->dropIndex('pembayaran_produk_id_foreign');
            $table->dropColumn(['agent_id', 'produk_id']);
        });
        
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('selesai', 'menunggu pembayaran', 'gagal') DEFAULT 'menunggu pembayaran'");
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

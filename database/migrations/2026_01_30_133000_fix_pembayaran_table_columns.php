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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Drop index 'pembayaran_produk_id_foreign'
            // We use array syntax which Laravel maps to the name, OR explicit name.
            // Since we know the name is 'pembayaran_produk_id_foreign', we use string.
            // Note: dropForeign is skipped because checks showed it's already gone.
            
            // Check if index exists to avoid errors if re-running
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('pembayaran');
            
            if (array_key_exists('pembayaran_produk_id_foreign', $indexes)) {
                $table->dropIndex('pembayaran_produk_id_foreign');
            }
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            // Increase ID length
            $table->string('id', 32)->change();
            
            // Change produk_id to TEXT
            $table->text('produk_id')->change();
            
            // Change msisdn to TEXT
            $table->text('msisdn')->nullable()->change();
            
            // Increase agent_id length
            $table->string('agent_id', 50)->change();
        });

        // Update status_pembayaran ENUM to match server
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('WAITING', 'VERIFY', 'SUCCESS', 'FAILED', 'EXPIRED') DEFAULT 'WAITING'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('id', 10)->change();
            $table->string('produk_id', 20)->change();
            $table->string('msisdn', 255)->nullable()->change();
            $table->string('agent_id', 10)->change();
        });
    }
};

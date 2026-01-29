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
                $table->string('agent_id')->nullable()->after('batch_id');
            }
            if (!Schema::hasColumn('pembayaran', 'produk_id')) {
                $table->string('produk_id', 20)->nullable()->after('agent_id');
            }
            
            // Add product detail columns
            if (!Schema::hasColumn('pembayaran', 'mslsdn')) {
                $table->string('mslsdn')->nullable()->after('produk_id');
            }
            if (!Schema::hasColumn('pembayaran', 'nama_paket')) {
                $table->string('nama_paket')->nullable()->after('mslsdn');
            }
            if (!Schema::hasColumn('pembayaran', 'tipe_paket')) {
                $table->string('tipe_paket')->nullable()->after('nama_paket');
            }
            if (!Schema::hasColumn('pembayaran', 'harga_modal')) {
                $table->integer('harga_modal')->default(0)->after('tipe_paket');
            }
            if (!Schema::hasColumn('pembayaran', 'harga_jual')) {
                $table->integer('harga_jual')->default(0)->after('harga_modal');
            }
            
            // Add metode_pembayaran if not exists (required by add_external_fields migration)
            if (!Schema::hasColumn('pembayaran', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('harga_jual');
            }
            
            // Drop old columns that don't exist in new structure
            if (Schema::hasColumn('pembayaran', 'nama_batch')) {
                $table->dropColumn('nama_batch');
            }
            if (Schema::hasColumn('pembayaran', 'sub_total')) {
                $table->dropColumn('sub_total');
            }
            if (Schema::hasColumn('pembayaran', 'biaya_platform')) {
                $table->dropColumn('biaya_platform');
            }
            if (Schema::hasColumn('pembayaran', 'bank')) {
                $table->dropColumn('bank');
            }
            if (Schema::hasColumn('pembayaran', 'no_rekening')) {
                $table->dropColumn('no_rekening');
            }
            if (Schema::hasColumn('pembayaran', 'va')) {
                $table->dropColumn('va');
            }
        });
        
        // Change column types to integer
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN profit INT DEFAULT 0");
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN total_pembayaran INT DEFAULT 0");
        
        // Add or update status_pembayaran enum
        $columns = DB::select("SHOW COLUMNS FROM pembayaran LIKE 'status_pembayaran'");
        if (empty($columns)) {
            // Add status_pembayaran if doesn't exist
            DB::statement("ALTER TABLE pembayaran ADD COLUMN status_pembayaran ENUM('berhasil', 'proses', 'gagal') DEFAULT 'proses' AFTER total_pembayaran");
        } else {
            // Update enum values for status_pembayaran
            DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('berhasil', 'proses', 'gagal') DEFAULT 'proses'");
        }
        
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
            // Remove added columns
            $table->dropColumn([
                'agent_id', 
                'produk_id', 
                'mslsdn', 
                'nama_paket', 
                'tipe_paket', 
                'harga_modal', 
                'harga_jual'
            ]);
            
            // Restore old columns
            $table->string('nama_batch');
            $table->bigInteger('sub_total');
            $table->bigInteger('biaya_platform')->default(0);
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('va')->nullable();
        });
        
        // Restore old column types
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN profit BIGINT");
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN total_pembayaran BIGINT");
        
        // Restore old enum values
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('selesai', 'menunggu pembayaran', 'gagal') DEFAULT 'menunggu pembayaran'");
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

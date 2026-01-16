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
            // Add new columns
            $table->string('link_referal')->nullable()->after('lat');
            $table->string('rekening_agent')->nullable()->after('link_referal');
            $table->date('date_approve')->nullable()->after('rekening_agent');
            
            // Modify existing columns to nullable
            $table->string('nama_travel')->nullable()->change();
            $table->string('jenis_travel')->nullable()->change();
            $table->integer('total_traveller')->nullable()->change();
            
            // Change status default to 'pending'
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['link_referal', 'rekening_agent', 'date_approve']);
            
            // Revert columns to not nullable (if needed for rollback)
            $table->string('nama_travel')->nullable(false)->change();
            $table->string('jenis_travel')->nullable(false)->change();
            $table->integer('total_traveller')->default(0)->nullable(false)->change();
            
            // Revert status default to 'active'
            $table->string('status')->default('active')->change();
        });
    }
};

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
        // Update reward table - set poin default to 0
        Schema::table('reward', function (Blueprint $table) {
            $table->integer('poin')->default(0)->change();
        });

        // Recreate withdraw table with varchar(10) ID to match server
        Schema::dropIfExists('withdraw');
        
        Schema::create('withdraw', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('rekening_id');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->text('alasan_reject')->nullable();
            $table->string('status')->default('pending');
            $table->date('date_approve')->nullable();
            $table->timestamps();

            $table->index('agent_id');
            $table->index('rekening_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate withdraw with auto-increment ID
        Schema::dropIfExists('withdraw');
        
        Schema::create('withdraw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agent')->onDelete('cascade');
            $table->foreignId('rekening_id')->constrained('rekening')->onDelete('cascade');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->text('alasan_reject')->nullable();
            $table->string('status')->default('pending');
            $table->date('date_approve')->nullable();
            $table->timestamps();
        });

        // Revert poin to not have default
        Schema::table('reward', function (Blueprint $table) {
            $table->integer('poin')->default(null)->change();
        });
    }
};

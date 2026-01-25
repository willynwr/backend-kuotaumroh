<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah tipe data ID dari BIGINT ke VARCHAR untuk agent, affiliate, freelance
     */
    public function up(): void
    {
        // 1. Drop foreign keys terlebih dahulu
        Schema::table('agent', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $this->dropForeignKeyIfExists('agent', 'agent_affiliate_id_foreign');
            $this->dropForeignKeyIfExists('agent', 'agent_freelance_id_foreign');
            $this->dropForeignKeyIfExists('agent', 'agents_affiliate_id_foreign');
            $this->dropForeignKeyIfExists('agent', 'agents_freelance_id_foreign');
        });

        Schema::table('rekening', function (Blueprint $table) {
            $this->dropForeignKeyIfExists('rekening', 'rekening_agent_id_foreign');
            $this->dropForeignKeyIfExists('rekening', 'rekenings_agent_id_foreign');
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $this->dropForeignKeyIfExists('withdraw', 'withdraw_agent_id_foreign');
            $this->dropForeignKeyIfExists('withdraw', 'withdraw_rekening_id_foreign');
            $this->dropForeignKeyIfExists('withdraw', 'withdraws_agent_id_foreign');
            $this->dropForeignKeyIfExists('withdraw', 'withdraws_rekening_id_foreign');
        });

        // 2. Ubah tipe ID pada tabel utama
        Schema::table('affiliate', function (Blueprint $table) {
            $table->string('id', 36)->change();
        });

        Schema::table('freelance', function (Blueprint $table) {
            $table->string('id', 36)->change();
        });

        Schema::table('agent', function (Blueprint $table) {
            $table->string('id', 36)->change();
            $table->string('affiliate_id', 36)->nullable()->change();
            $table->string('freelance_id', 36)->nullable()->change();
        });

        Schema::table('rekening', function (Blueprint $table) {
            $table->string('id', 36)->change();
            $table->string('agent_id', 36)->change();
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $table->string('id', 36)->change();
            $table->string('agent_id', 36)->change();
            $table->string('rekening_id', 36)->change();
        });

        // 3. Tambah kembali foreign keys
        Schema::table('agent', function (Blueprint $table) {
            $table->foreign('affiliate_id')->references('id')->on('affiliate')->onDelete('cascade');
            $table->foreign('freelance_id')->references('id')->on('freelance')->onDelete('cascade');
        });

        Schema::table('rekening', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agent')->onDelete('cascade');
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agent')->onDelete('cascade');
            $table->foreign('rekening_id')->references('id')->on('rekening')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop foreign keys
        Schema::table('agent', function (Blueprint $table) {
            $this->dropForeignKeyIfExists('agent', 'agent_affiliate_id_foreign');
            $this->dropForeignKeyIfExists('agent', 'agent_freelance_id_foreign');
        });

        Schema::table('rekening', function (Blueprint $table) {
            $this->dropForeignKeyIfExists('rekening', 'rekening_agent_id_foreign');
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $this->dropForeignKeyIfExists('withdraw', 'withdraw_agent_id_foreign');
            $this->dropForeignKeyIfExists('withdraw', 'withdraw_rekening_id_foreign');
        });

        // 2. Kembalikan tipe ID ke BIGINT
        Schema::table('affiliate', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('freelance', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('agent', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->unsignedBigInteger('affiliate_id')->nullable()->change();
            $table->unsignedBigInteger('freelance_id')->nullable()->change();
        });

        Schema::table('rekening', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->unsignedBigInteger('agent_id')->change();
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->unsignedBigInteger('agent_id')->change();
            $table->unsignedBigInteger('rekening_id')->change();
        });

        // 3. Tambah kembali foreign keys
        Schema::table('agent', function (Blueprint $table) {
            $table->foreign('affiliate_id')->references('id')->on('affiliate')->onDelete('cascade');
            $table->foreign('freelance_id')->references('id')->on('freelance')->onDelete('cascade');
        });

        Schema::table('rekening', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agent')->onDelete('cascade');
        });

        Schema::table('withdraw', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agent')->onDelete('cascade');
            $table->foreign('rekening_id')->references('id')->on('rekening')->onDelete('cascade');
        });
    }

    /**
     * Helper function to drop foreign key if exists
     */
    private function dropForeignKeyIfExists(string $table, string $foreignKey): void
    {
        $keyExists = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.TABLE_CONSTRAINTS 
             WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
             AND TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?",
            [$table, $foreignKey]
        );

        if (count($keyExists) > 0) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }
    }
};

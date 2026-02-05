<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set existing NULL saldo to 0
        DB::table('agents')->whereNull('saldo')->update(['saldo' => 0]);

        // Ensure saldo has NOT NULL and DEFAULT 0
        DB::statement('ALTER TABLE agents MODIFY saldo INT NOT NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert saldo to nullable without default
        DB::statement('ALTER TABLE agents MODIFY saldo INT NULL');
    }
};

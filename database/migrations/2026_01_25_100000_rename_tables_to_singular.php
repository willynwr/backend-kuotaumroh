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
        // Rename all tables to singular form to match database convention
        if (Schema::hasTable('agents')) {
            Schema::rename('agents', 'agent');
        }
        
        if (Schema::hasTable('affiliates')) {
            Schema::rename('affiliates', 'affiliate');
        }
        
        if (Schema::hasTable('freelances')) {
            Schema::rename('freelances', 'freelance');
        }
        
        if (Schema::hasTable('rekenings')) {
            Schema::rename('rekenings', 'rekening');
        }
        
        if (Schema::hasTable('margins')) {
            Schema::rename('margins', 'margin');
        }
        
        // Check if withdraws table exists before renaming
        if (Schema::hasTable('withdraws')) {
            Schema::rename('withdraws', 'withdraw');
        }
        
        // Check if rewards table exists before renaming
        if (Schema::hasTable('rewards')) {
            Schema::rename('rewards', 'reward');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the renaming
        Schema::rename('agent', 'agents');
        Schema::rename('affiliate', 'affiliates');
        Schema::rename('freelance', 'freelances');
        Schema::rename('rekening', 'rekenings');
        Schema::rename('margin', 'margins');
        
        if (Schema::hasTable('withdraw')) {
            Schema::rename('withdraw', 'withdraws');
        }
        
        if (Schema::hasTable('reward')) {
            Schema::rename('reward', 'rewards');
        }
    }
};

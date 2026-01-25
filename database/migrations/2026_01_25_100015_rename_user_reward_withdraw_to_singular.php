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
        // Rename users to user
        if (Schema::hasTable('users') && !Schema::hasTable('user')) {
            Schema::rename('users', 'user');
        }

        // Rename rewards to reward
        if (Schema::hasTable('rewards') && !Schema::hasTable('reward')) {
            Schema::rename('rewards', 'reward');
        }

        // Rename withdraws to withdraw
        if (Schema::hasTable('withdraws') && !Schema::hasTable('withdraw')) {
            Schema::rename('withdraws', 'withdraw');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename back to plural
        if (Schema::hasTable('user') && !Schema::hasTable('users')) {
            Schema::rename('user', 'users');
        }

        if (Schema::hasTable('reward') && !Schema::hasTable('rewards')) {
            Schema::rename('reward', 'rewards');
        }

        if (Schema::hasTable('withdraw') && !Schema::hasTable('withdraws')) {
            Schema::rename('withdraw', 'withdraws');
        }
    }
};

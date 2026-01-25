<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update tokenable_id to support string IDs (AGT00001, AFT00001, FRL00001, etc.)
     */
    public function up(): void
    {
        // Change tokenable_id from bigint to varchar to support custom string IDs
        DB::statement('ALTER TABLE personal_access_tokens MODIFY tokenable_id VARCHAR(20) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to bigint - WARNING: This will fail if there are string IDs in the table
        DB::statement('ALTER TABLE personal_access_tokens MODIFY tokenable_id BIGINT UNSIGNED NOT NULL');
    }
};

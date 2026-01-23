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
        Schema::create('margin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('cascade');
            $table->foreignId('affiliate_id')->nullable()->constrained('affiliates')->onDelete('cascade');
            $table->foreignId('freelance_id')->nullable()->constrained('freelances')->onDelete('cascade');
            $table->integer('harga_eup');
            $table->decimal('persentase_margin_star', 5, 2)->default(0);
            $table->integer('margin_star')->default(0);
            $table->integer('margin_total')->default(0);
            $table->integer('fee_travel')->default(0);
            $table->decimal('persentase_fee_travel', 5, 2)->default(0);
            $table->decimal('persentase_fee_affiliate', 5, 2)->default(0);
            $table->integer('fee_affiliate')->default(0);
            $table->decimal('persentase_fee_host', 5, 2)->default(0);
            $table->integer('fee_host')->default(0);
            $table->integer('harga_tp_travel')->default(0);
            $table->integer('harga_tp_host')->default(0);
            $table->integer('poin')->default(0);
            $table->integer('profit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('margin');
    }
};

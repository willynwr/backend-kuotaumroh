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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nama_pic');
            $table->string('no_hp');
            $table->string('nama_travel');
            $table->string('jenis_travel');
            $table->integer('total_traveller')->default(0);
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->text('alamat_lengkap');
            $table->string('logo')->nullable();
            $table->string('surat_ppiu')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};

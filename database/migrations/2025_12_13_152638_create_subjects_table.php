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
        Schema::create('subjects', function (Blueprint $table) {
            // Primary Key (sesuai FK di materials)
            $table->char('code', 5)->primary();

            // Data utama mata pelajaran
            $table->string('name');
            $table->text('description')->nullable();

            // Optional info
            $table->integer('credit')->nullable(); // SKS / bobot
            $table->enum('status', ['Aktif', 'NonAktif'])->default('Aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

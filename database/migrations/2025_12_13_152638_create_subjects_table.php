<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->char('code', 5)->primary();

            // Data utama mata pelajaran
            $table->string('name');
            $table->text('description')->nullable();

            // Optional info
            $table->integer('credit')->nullable(); 
            $table->enum('status', ['Aktif', 'NonAktif'])->default('Aktif');

            $table->timestamps();
        });
    }
  
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

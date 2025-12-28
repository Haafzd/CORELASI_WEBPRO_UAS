<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            // Link to schedule session
            $table->foreignId('schedule_session_id')->constrained('schedule_sessions')->cascadeOnDelete();
            
            // BAP Content
            $table->date('journal_date'); // The actual date of teaching
            $table->string('topic'); // "Materi"
            $table->string('observation_notes')->nullable(); // "Indikator Pencapaian"
            $table->string('location')->nullable(); // "Tempat"
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('teaching_journals');
    }
};

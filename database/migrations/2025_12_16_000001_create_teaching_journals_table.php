<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_session_id')->constrained('schedule_sessions')->cascadeOnDelete();
            $table->date('journal_date'); 
            $table->string('topic'); 
            $table->string('observation_notes')->nullable();
            $table->string('location')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('teaching_journals');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            // Link to Journal (One journal has many attendance records)
            $table->foreignId('teaching_journal_id')->constrained('teaching_journals')->cascadeOnDelete();
            
            // Student Identifier (NIS is string in Student model)
            $table->string('student_nis', 50);
            $table->foreign('student_nis')->references('nis')->on('students')->cascadeOnDelete();

            // Status
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('alpa'); // Default alpa as requested
            $table->string('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance_records');
    }
};

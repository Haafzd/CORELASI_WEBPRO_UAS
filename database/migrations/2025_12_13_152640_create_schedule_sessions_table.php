<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('schedule_sessions', function(Blueprint $t){
      $t->id();
      $t->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
      $t->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
      $t->char('subject_code',5);
      $t->foreign('subject_code')->references('code')->on('subjects')->cascadeOnDelete();
      $t->string('teacher_nip',50);
      $t->foreign('teacher_nip')->references('nip')->on('teachers')->cascadeOnDelete();
      $t->enum('weekday',["Senin","Selasa","Rabu","Kamis","Jumat"])->nullable();
      $t->date('specific_date')->nullable();
      $t->time('start_time');
      $t->time('end_time');
      $t->boolean('is_active')->default(true);
      $t->text('remark')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('schedule_sessions'); }
};

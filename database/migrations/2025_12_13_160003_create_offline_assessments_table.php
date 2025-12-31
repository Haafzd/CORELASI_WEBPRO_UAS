<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('offline_assessments', function(Blueprint $t){
      $t->id();
      $t->string('student_nis',50);
      $t->foreign('student_nis')->references('nis')->on('students')->cascadeOnDelete();
      $t->char('subject_code',5);
      $t->foreign('subject_code')->references('code')->on('subjects')->cascadeOnDelete();
      $t->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
      $t->string('graded_by_nip',50);
      $t->foreign('graded_by_nip')->references('nip')->on('teachers')->cascadeOnDelete();
      $t->enum('type',["UH","Praktikum","UTS","UAS","Nilai Akhir Semester","Lainnya"]);
      $t->decimal('score',5,2);
      $t->date('graded_on');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('offline_assessments'); }
};

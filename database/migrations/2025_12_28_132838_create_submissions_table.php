<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('submissions', function(Blueprint $t){
      $t->id();
      $t->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
      $t->string('student_nis',50);
      $t->foreign('student_nis')->references('nis')->on('students')->cascadeOnDelete();
      $t->string('file_path');
      $t->string('original_name');
      $t->timestamp('submitted_at')->nullable();
      $t->text('student_note')->nullable();
      $t->enum('status',["Tepat Waktu","Terlambat","Belum Mengumpulkan","Sudah Dinilai"])->default('Belum Mengumpulkan');
      $t->decimal('score',5,2)->nullable();
      $t->text('feedback')->nullable();
      $t->timestamps();
      $t->unique(['assignment_id','student_nis']);
    });
  }
  public function down(): void { Schema::dropIfExists('submissions'); }
};

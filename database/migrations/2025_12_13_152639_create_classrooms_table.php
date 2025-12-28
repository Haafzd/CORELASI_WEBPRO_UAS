<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('classrooms', function(Blueprint $t){
      $t->id();
      $t->string('name')->unique();
      $t->year('cohort_year');
      $t->string('major')->nullable();
      $t->string('homeroom_teacher_nip',50)->nullable()->unique();
      $t->foreign('homeroom_teacher_nip')->references('nip')->on('teachers');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('classrooms'); }
};

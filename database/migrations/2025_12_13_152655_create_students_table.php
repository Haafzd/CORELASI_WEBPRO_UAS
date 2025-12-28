<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('students', function(Blueprint $t){
      $t->string('nis',50)->primary();
      $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $t->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
      $t->year('entry_cohort');
      $t->text('short_bio')->nullable();
    });
  }
  public function down(): void { Schema::dropIfExists('students'); }
};

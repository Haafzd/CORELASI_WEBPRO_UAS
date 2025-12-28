<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('semesters', function(Blueprint $t){
      $t->id();
      $t->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
      $t->enum('name',['Ganjil','Genap']);
      $t->date('start_date');
      $t->date('end_date');
      $t->boolean('is_active')->default(false);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('semesters'); }
};

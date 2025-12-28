<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('academic_years', function(Blueprint $t){
      $t->id();
      $t->string('label',15)->unique(); // "YYYY/YYYY"
      $t->date('start_date');
      $t->date('end_date');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('academic_years'); }
};

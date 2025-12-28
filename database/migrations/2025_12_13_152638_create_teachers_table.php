<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('teachers', function(Blueprint $t){
      $t->string('nip',50)->primary();
      $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      // $t->foreign('nip')->references('id')->on('users')->cascadeOnDelete(); // REMOVED: Type mismatch
      $t->string('phone',20)->nullable();
      $t->boolean('is_duty_teacher')->default(false);
    });
  }
  public function down(): void { Schema::dropIfExists('teachers'); }
};

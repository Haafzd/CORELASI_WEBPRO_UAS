<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('assignments', function(Blueprint $t){
      $t->id();
      $t->foreignId('schedule_session_id')->constrained('schedule_sessions')->cascadeOnDelete();
      $t->string('title');
      $t->text('instruction')->nullable();
      $t->string('external_problem_link',2048)->nullable();
      $t->timestamp('deadline_at');
      $t->enum('publish_status',["Draft","Published"])->default('Draft');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('assignments'); }
};

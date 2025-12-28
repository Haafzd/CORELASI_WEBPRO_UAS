<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('learning_materials', function(Blueprint $t){
      $t->id();
      $t->foreignId('schedule_session_id')->constrained('schedule_sessions')->cascadeOnDelete();
      $t->string('title');
      $t->text('description')->nullable();
      $t->string('external_link',2048);
      $t->enum('publish_status',["Draft","Published"])->default('Draft');
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('learning_materials'); }
};

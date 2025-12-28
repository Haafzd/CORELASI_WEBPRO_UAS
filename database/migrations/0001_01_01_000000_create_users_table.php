<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('users', function (Blueprint $t) {
      $t->id(); 
      $t->string('username',50)->unique();
      $t->string('password');
      $t->string('full_name');
      $t->string('email')->nullable()->unique();
      $t->enum('role',['Admin','Guru','Siswa']);
      $t->string('photo_path')->nullable();
      $t->enum('account_status',['Aktif','NonAktif'])->default('Aktif');
      $t->rememberToken();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('users'); }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  
    public function up(): void
    {
        DB::statement("ALTER TABLE learning_materials MODIFY external_link VARCHAR(2048) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE learning_materials MODIFY external_link VARCHAR(2048) NOT NULL");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si ya se creó por error, la eliminamos
        Schema::dropIfExists('departments');

        // Renombramos la tabla correcta
        Schema::rename('department', 'departments');
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('departments', 'department');
    }
};

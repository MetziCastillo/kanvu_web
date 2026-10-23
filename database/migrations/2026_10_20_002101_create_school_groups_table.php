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
        Schema::create('school_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('career_id')->constrained('careers');
            $table->foreignId('grade_id')->constrained('grades');
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('school_year_id')->constrained('school_years');

            $table->boolean('enable')->default(true);
            $table->timestamps();

            $table->unique([
                'career_id',
                'grade_id',
                'group_id',
                'school_year_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_groups');
    }
};

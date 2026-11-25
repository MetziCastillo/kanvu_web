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
        Schema::create('psychology_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('area_id')->constrained('areas')->restrictOnDelete();
            $table->foreignId('modality_id')->constrained('modalities')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('statuses')->restrictOnDelete();

            $table->date('appointment_date');
            $table->time('time_start');
            $table->time('time_end');

            $table->text('reason');
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'appointment_date', 'time_start'], 'unique_employee_slot');
            $table->index(['student_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychology_appointments');
    }
};

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
        Schema::create('recurring_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('student_name')->nullable();
            $table->string('course_type');
            $table->time('class_time');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->integer('duration_hours')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('recurrence_type', ['weekly', 'weeks_count', 'months_count', 'endless']);
            $table->integer('recurrence_value')->nullable(); // For weeks_count and months_count
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_courses');
    }
};

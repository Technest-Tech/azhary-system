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
        Schema::create('waiting_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('student_name')->nullable();
            $table->string('name');
            $table->date('course_date');
            $table->time('class_time');
            $table->integer('duration_hours')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->string('course_type');
            $table->string('status')->default('Pending');
            $table->string('admin_status')->default('approved');
            $table->text('homework')->nullable();
            $table->foreignId('evaluation_id')->nullable()->constrained('evaluations')->onDelete('set null');
            $table->text('content')->nullable();
            $table->text('notes')->nullable();
            $table->string('souvenir_image')->nullable();
            $table->decimal('income', 10, 2)->default(0);
            $table->boolean('is_recurring')->default(false);
            $table->foreignId('recurring_course_id')->nullable()->constrained('recurring_courses')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_list');
    }
};

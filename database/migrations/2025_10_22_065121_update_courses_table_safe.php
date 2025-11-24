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
        Schema::table('courses', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('courses', 'student_name')) {
                $table->string('student_name')->after('student_id');
            }
            if (!Schema::hasColumn('courses', 'n_value')) {
                $table->integer('n_value')->nullable()->after('student_name');
            }
            if (!Schema::hasColumn('courses', 'class_time')) {
                $table->time('class_time')->nullable()->after('n_value');
            }
            if (!Schema::hasColumn('courses', 'course_type')) {
                $table->string('course_type')->default('Quran')->after('class_time');
            }
            if (!Schema::hasColumn('courses', 'duration_hours')) {
                $table->integer('duration_hours')->default(0)->after('course_date');
            }
            if (!Schema::hasColumn('courses', 'duration_minutes')) {
                $table->integer('duration_minutes')->default(0)->after('duration_hours');
            }
            if (!Schema::hasColumn('courses', 'status')) {
                $table->enum('status', ['Present', 'Absent', 'Late'])->default('Present')->after('duration_minutes');
            }
            if (!Schema::hasColumn('courses', 'homework')) {
                $table->text('homework')->nullable()->after('status');
            }
            if (!Schema::hasColumn('courses', 'evaluation_id')) {
                $table->foreignId('evaluation_id')->nullable()->constrained('evaluations')->onDelete('set null')->after('homework');
            }
            if (!Schema::hasColumn('courses', 'content')) {
                $table->text('content')->nullable()->after('evaluation_id');
            }
            if (!Schema::hasColumn('courses', 'notes')) {
                $table->text('notes')->nullable()->after('content');
            }
            if (!Schema::hasColumn('courses', 'souvenir_image')) {
                $table->string('souvenir_image')->nullable()->after('notes');
            }
            
            // Rename existing fields to match form (only if they exist)
            if (Schema::hasColumn('courses', 'course_name') && !Schema::hasColumn('courses', 'name')) {
                $table->renameColumn('course_name', 'name');
            }
            if (Schema::hasColumn('courses', 'hours') && !Schema::hasColumn('courses', 'total_hours')) {
                $table->renameColumn('hours', 'total_hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop columns if they exist
            $columns = [
                'student_name',
                'n_value',
                'class_time',
                'course_type',
                'duration_hours',
                'duration_minutes',
                'status',
                'homework',
                'evaluation_id',
                'content',
                'notes',
                'souvenir_image'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('courses', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Rename back if they exist
            if (Schema::hasColumn('courses', 'name') && !Schema::hasColumn('courses', 'course_name')) {
                $table->renameColumn('name', 'course_name');
            }
            if (Schema::hasColumn('courses', 'total_hours') && !Schema::hasColumn('courses', 'hours')) {
                $table->renameColumn('total_hours', 'hours');
            }
        });
    }
};

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
            if (!Schema::hasColumn('courses', 'is_recurring')) {
                $table->boolean('is_recurring')->default(false)->after('souvenir_image');
            }
            if (!Schema::hasColumn('courses', 'recurring_course_id')) {
                $table->foreignId('recurring_course_id')->nullable()->constrained('recurring_courses')->onDelete('set null')->after('is_recurring');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'recurring_course_id')) {
                $table->dropForeign(['recurring_course_id']);
                $table->dropColumn('recurring_course_id');
            }
            if (Schema::hasColumn('courses', 'is_recurring')) {
                $table->dropColumn('is_recurring');
            }
        });
    }
};

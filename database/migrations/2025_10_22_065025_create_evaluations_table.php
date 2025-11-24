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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "MashAllah", "Mumtaz (Excellent)"
            $table->string('description')->nullable(); // e.g., "100%", "85% – 99%"
            $table->integer('min_percentage')->nullable(); // Minimum percentage for this evaluation
            $table->integer('max_percentage')->nullable(); // Maximum percentage for this evaluation
            $table->string('icon')->nullable(); // Icon class or name
            $table->string('color')->nullable(); // Color for UI display
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // For ordering in dropdown
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

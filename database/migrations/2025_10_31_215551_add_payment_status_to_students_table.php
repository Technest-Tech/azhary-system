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
        Schema::table('students', function (Blueprint $table) {
            $table->enum('payment_status', ['PAYÉ', 'ARRÊTÉ', 'EN ATTENTE DE PAYEMENT', 'SUSPENDU'])
                  ->default('EN ATTENTE DE PAYEMENT')
                  ->after('package_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};

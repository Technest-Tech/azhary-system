<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, create payment_statuses table if not exists (it should be created by previous migration)
        if (!Schema::hasTable('payment_statuses')) {
            Schema::create('payment_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->string('color', 7)->default('#64748b');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Seed default payment statuses
        $defaultStatuses = [
            ['name' => 'PAYÉ', 'display_name' => 'Paid', 'color' => '#10B981', 'sort_order' => 1],
            ['name' => 'ARRÊTÉ', 'display_name' => 'Stopped', 'color' => '#EF4444', 'sort_order' => 2],
            ['name' => 'EN ATTENTE DE PAYEMENT', 'display_name' => 'Pending Payment', 'color' => '#F59E0B', 'sort_order' => 3],
            ['name' => 'SUSPENDU', 'display_name' => 'Suspended', 'color' => '#EC4899', 'sort_order' => 4],
        ];

        foreach ($defaultStatuses as $status) {
            DB::table('payment_statuses')->insertOrIgnore([
                'name' => $status['name'],
                'display_name' => $status['display_name'],
                'color' => $status['color'],
                'is_active' => true,
                'sort_order' => $status['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add payment_status_id column (nullable for onDelete set null)
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'payment_status_id')) {
                $table->foreignId('payment_status_id')->nullable()->after('package_rate');
            }
        });

        // Migrate existing enum data to foreign key
        if (Schema::hasColumn('students', 'payment_status')) {
            $students = DB::table('students')->whereNotNull('payment_status')->get();
            foreach ($students as $student) {
                $statusId = DB::table('payment_statuses')
                    ->where('name', $student->payment_status)
                    ->value('id');
                
                if ($statusId) {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update(['payment_status_id' => $statusId]);
                } else {
                    // Use default status if not found
                    $defaultStatusId = DB::table('payment_statuses')
                        ->where('name', 'EN ATTENTE DE PAYEMENT')
                        ->value('id');
                    if ($defaultStatusId) {
                        DB::table('students')
                            ->where('id', $student->id)
                            ->update(['payment_status_id' => $defaultStatusId]);
                    }
                }
            }

            // Drop enum column
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }

        // Ensure column is nullable and add foreign key constraint
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('payment_status_id')->nullable()->change();
            $table->foreign('payment_status_id')
                  ->references('id')
                  ->on('payment_statuses')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'payment_status_id')) {
                $table->dropForeign(['payment_status_id']);
                $table->dropColumn('payment_status_id');
            }
        });

        // Re-add enum column
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'payment_status')) {
                $table->enum('payment_status', ['PAYÉ', 'ARRÊTÉ', 'EN ATTENTE DE PAYEMENT', 'SUSPENDU'])
                      ->default('EN ATTENTE DE PAYEMENT')
                      ->after('package_rate');
            }
        });

        // Migrate data back from foreign key to enum
        $students = DB::table('students')
            ->join('payment_statuses', 'students.payment_status_id', '=', 'payment_statuses.id')
            ->select('students.id', 'payment_statuses.name')
            ->get();

        foreach ($students as $student) {
            DB::table('students')
                ->where('id', $student->id)
                ->update(['payment_status' => $student->name]);
        }

        Schema::dropIfExists('payment_statuses');
    }
};

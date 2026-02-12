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
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql' || $driver === 'mariadb') {
            // For MySQL/MariaDB: Update ENUM to include 'Free' and remove 'Late' and 'Pending'
            // First, update any existing 'Late' or 'Pending' values to 'Absent'
            DB::table('courses')
                ->whereIn('status', ['Late', 'Pending'])
                ->update(['status' => 'Absent']);
            
            // Modify the ENUM column to include 'Free' and remove 'Late' and 'Pending'
            DB::statement("ALTER TABLE `courses` MODIFY COLUMN `status` ENUM('Present', 'Absent', 'Free') DEFAULT 'Present'");
        } else {
            // For SQLite and other databases: Update existing values first
            DB::table('courses')
                ->whereIn('status', ['Late', 'Pending'])
                ->update(['status' => 'Absent']);
            
            // SQLite doesn't support ENUM, so we just ensure data integrity
            // The constraint is handled at the application level
            // If you need to add a CHECK constraint for SQLite:
            // DB::statement("ALTER TABLE `courses` ADD CONSTRAINT check_status CHECK (status IN ('Present', 'Absent', 'Free'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql' || $driver === 'mariadb') {
            // Update any 'Free' values to 'Absent' before reverting
            DB::table('courses')
                ->where('status', 'Free')
                ->update(['status' => 'Absent']);
            
            // Revert to original ENUM values
            DB::statement("ALTER TABLE `courses` MODIFY COLUMN `status` ENUM('Present', 'Absent', 'Late') DEFAULT 'Present'");
        } else {
            // For SQLite: Just update the data
            DB::table('courses')
                ->where('status', 'Free')
                ->update(['status' => 'Absent']);
        }
    }
};

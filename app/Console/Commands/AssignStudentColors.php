<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StudentColorService;

class AssignStudentColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:assign-colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign unique colors to all students, ensuring no two students of the same teacher have the same color';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assigning colors to students...');
        
        $results = StudentColorService::assignColorsToAllStudents();
        
        $this->info("✓ Assigned colors to {$results['assigned']} students");
        
        if ($results['updated'] > 0) {
            $this->info("✓ Updated colors for {$results['updated']} students (resolved duplicates)");
        }
        
        if (!empty($results['errors'])) {
            $this->error('Errors occurred:');
            foreach ($results['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }
        
        $this->info('Color assignment completed!');
        
        return Command::SUCCESS;
    }
}

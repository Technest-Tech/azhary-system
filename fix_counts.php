<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Http\Controllers\AdminController;

$students = Student::all();
$adminCtrl = new AdminController();

$updated = 0;

foreach ($students as $student) {
    if ($student->package_number > 0) {
        $adminCtrl->recalculateNValues($student->id);
        $updated++;
    }
}

echo "Successfully recalculated and patched courses for {$updated} active students based on the newly isolated package logic.\n";

?>

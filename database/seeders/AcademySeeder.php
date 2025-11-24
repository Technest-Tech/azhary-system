<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Evaluation;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin accounts
        Admin::firstOrCreate(
            ['email' => 'admin@academy.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Create Teacher accounts
        $teacher1 = Teacher::firstOrCreate(
            ['email' => 'teacher@academy.com'],
            [
                'name' => 'Teacher One',
                'phone' => '+1234567890',
                'date_of_birth' => '1985-05-15',
                'hourly_rate' => 30.00,
                'password' => 'password', // Let Laravel handle hashing via casts
            ]
        );

        $teacher2 = Teacher::firstOrCreate(
            ['email' => 'teacher2@academy.com'],
            [
                'name' => 'Teacher Two',
                'phone' => '+1234567891',
                'date_of_birth' => '1990-08-20',
                'hourly_rate' => 35.00,
                'password' => 'password', // Let Laravel handle hashing via casts
            ]
        );

        // Get payment statuses
        $paidStatus = \App\Models\PaymentStatus::where('name', 'PAYÉ')->first();
        $pendingStatus = \App\Models\PaymentStatus::where('name', 'EN ATTENTE DE PAYEMENT')->first();

        // Create Student accounts
        Student::firstOrCreate(
            ['email' => 'student1@academy.com'],
            [
                'name' => 'Student One',
                'phone' => '+1234567892',
                'date_of_birth' => '2005-03-10',
                'section' => 'A',
                'package_number' => 1,
                'hour_rate' => 25.00,
                'package_rate' => 200.00,
                'payment_status_id' => $paidStatus ? $paidStatus->id : null,
                'teacher_id' => $teacher1->id,
                'teacher_rate' => 30.00,
                'password' => Hash::make('password'),
            ]
        );

        Student::firstOrCreate(
            ['email' => 'student2@academy.com'],
            [
                'name' => 'Student Two',
                'phone' => '+1234567893',
                'date_of_birth' => '2006-07-22',
                'section' => 'B',
                'package_number' => 2,
                'hour_rate' => 25.00,
                'package_rate' => 200.00,
                'payment_status_id' => $pendingStatus ? $pendingStatus->id : null,
                'teacher_id' => $teacher2->id,
                'teacher_rate' => 35.00,
                'password' => Hash::make('password'),
            ]
        );

        // Create Evaluation entries based on the form
        $evaluations = [
            [
                'name' => 'MashAllah',
                'description' => '100%',
                'min_percentage' => 100,
                'max_percentage' => 100,
                'icon' => 'crescent-moon',
                'color' => '#10B981',
                'sort_order' => 1,
            ],
            [
                'name' => 'Mumtaz (Excellent)',
                'description' => '85% – 99%',
                'min_percentage' => 85,
                'max_percentage' => 99,
                'icon' => 'sun',
                'color' => '#F59E0B',
                'sort_order' => 2,
            ],
            [
                'name' => 'Jayyid Jiddan (Very Good)',
                'description' => '80% – 84%',
                'min_percentage' => 80,
                'max_percentage' => 84,
                'icon' => 'check-circle',
                'color' => '#22C55E',
                'sort_order' => 3,
            ],
            [
                'name' => 'Jayyid (Good)',
                'description' => '70% – 79%',
                'min_percentage' => 70,
                'max_percentage' => 79,
                'icon' => 'book',
                'color' => '#3B82F6',
                'sort_order' => 4,
            ],
            [
                'name' => 'Mutawassit (Average)',
                'description' => '60% – 69%',
                'min_percentage' => 60,
                'max_percentage' => 69,
                'icon' => 'exclamation-triangle',
                'color' => '#F59E0B',
                'sort_order' => 5,
            ],
            [
                'name' => 'Da\'if (Weak)',
                'description' => '50% – 59%',
                'min_percentage' => 50,
                'max_percentage' => 59,
                'icon' => 'x-circle',
                'color' => '#EF4444',
                'sort_order' => 6,
            ],
            [
                'name' => 'Khatar! (In Great Danger)',
                'description' => '30% – 49%',
                'min_percentage' => 30,
                'max_percentage' => 49,
                'icon' => 'exclamation-circle',
                'color' => '#DC2626',
                'sort_order' => 7,
            ],
            [
                'name' => 'Ijtihad! (Efforts Needed)',
                'description' => '0% – 29%',
                'min_percentage' => 0,
                'max_percentage' => 29,
                'icon' => 'circle',
                'color' => '#991B1B',
                'sort_order' => 8,
            ],
        ];

        foreach ($evaluations as $evaluation) {
            Evaluation::firstOrCreate(
                ['name' => $evaluation['name']],
                $evaluation
            );
        }
    }
}

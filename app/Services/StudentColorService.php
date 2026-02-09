<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentColorService
{
    /**
     * Predefined color palette - vibrant, distinct colors
     */
    private static $colorPalette = [
        '#14b8a6', // Teal
        '#0d9488', // Dark Teal
        '#3b82f6', // Blue
        '#2563eb', // Dark Blue
        '#8b5cf6', // Purple
        '#7c3aed', // Dark Purple
        '#ec4899', // Pink
        '#db2777', // Dark Pink
        '#f59e0b', // Amber
        '#d97706', // Dark Amber
        '#10b981', // Green
        '#059669', // Dark Green
        '#ef4444', // Red
        '#dc2626', // Dark Red
        '#06b6d4', // Cyan
        '#0891b2', // Dark Cyan
        '#a855f7', // Violet
        '#9333ea', // Dark Violet
        '#f97316', // Orange
        '#ea580c', // Dark Orange
        '#84cc16', // Lime
        '#65a30d', // Dark Lime
        '#6366f1', // Indigo
        '#4f46e5', // Dark Indigo
        '#22c55e', // Emerald
        '#16a34a', // Dark Emerald
        '#eab308', // Yellow
        '#ca8a04', // Dark Yellow
        '#06b6d4', // Sky
        '#0284c7', // Dark Sky
    ];

    /**
     * Generate a unique color for a student based on their teacher
     * Ensures no two students of the same teacher have the same color
     */
    public static function generateUniqueColorForTeacher($teacherId): string
    {
        if (!$teacherId) {
            // If no teacher, return a default color
            return self::$colorPalette[0];
        }

        // Get all colors already assigned to students of this teacher
        $usedColors = Student::where('teacher_id', $teacherId)
            ->whereNotNull('color')
            ->pluck('color')
            ->toArray();

        // Find the first available color from the palette
        foreach (self::$colorPalette as $color) {
            if (!in_array($color, $usedColors)) {
                return $color;
            }
        }

        // If all colors are used, generate a random color
        // This is unlikely but handles edge cases
        return self::generateRandomColor();
    }

    /**
     * Generate a random color (fallback when palette is exhausted)
     */
    private static function generateRandomColor(): string
    {
        // Generate a random vibrant color
        $hue = rand(0, 360);
        $saturation = rand(60, 100);
        $lightness = rand(40, 60);
        
        return "hsl({$hue}, {$saturation}%, {$lightness}%)";
    }

    /**
     * Assign colors to all students that don't have one
     * Ensures uniqueness per teacher
     */
    public static function assignColorsToAllStudents(): array
    {
        $results = [
            'assigned' => 0,
            'updated' => 0,
            'errors' => []
        ];

        // Get all students grouped by teacher
        $studentsByTeacher = Student::whereNotNull('teacher_id')
            ->whereNull('color')
            ->get()
            ->groupBy('teacher_id');

        foreach ($studentsByTeacher as $teacherId => $students) {
            $usedColors = [];
            
            foreach ($students as $student) {
                // Find an unused color for this teacher
                $color = null;
                foreach (self::$colorPalette as $paletteColor) {
                    if (!in_array($paletteColor, $usedColors)) {
                        $color = $paletteColor;
                        $usedColors[] = $color;
                        break;
                    }
                }

                // If palette exhausted, generate random
                if (!$color) {
                    $color = self::generateRandomColor();
                }

                try {
                    $student->update(['color' => $color]);
                    $results['assigned']++;
                } catch (\Exception $e) {
                    $results['errors'][] = "Failed to assign color to student {$student->id}: " . $e->getMessage();
                }
            }
        }

        // Also handle students with duplicate colors per teacher
        $teachers = DB::table('students')
            ->whereNotNull('teacher_id')
            ->whereNotNull('color')
            ->select('teacher_id', 'color')
            ->groupBy('teacher_id', 'color')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($teachers as $duplicate) {
            $studentsWithSameColor = Student::where('teacher_id', $duplicate->teacher_id)
                ->where('color', $duplicate->color)
                ->get();

            // Keep first student's color, reassign others
            $firstStudent = $studentsWithSameColor->first();
            $usedColors = Student::where('teacher_id', $duplicate->teacher_id)
                ->where('id', '!=', $firstStudent->id)
                ->whereNotNull('color')
                ->pluck('color')
                ->toArray();

            foreach ($studentsWithSameColor->skip(1) as $student) {
                $color = null;
                foreach (self::$colorPalette as $paletteColor) {
                    if (!in_array($paletteColor, $usedColors)) {
                        $color = $paletteColor;
                        $usedColors[] = $color;
                        break;
                    }
                }

                if (!$color) {
                    $color = self::generateRandomColor();
                }

                try {
                    $student->update(['color' => $color]);
                    $results['updated']++;
                } catch (\Exception $e) {
                    $results['errors'][] = "Failed to update color for student {$student->id}: " . $e->getMessage();
                }
            }
        }

        return $results;
    }

    /**
     * Get a darker shade of a color for gradient end
     */
    public static function getDarkerShade($color): string
    {
        // If it's a hex color, darken it
        if (strpos($color, '#') === 0) {
            $hex = str_replace('#', '', $color);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            
            // Darken by 20%
            $r = max(0, min(255, $r * 0.8));
            $g = max(0, min(255, $g * 0.8));
            $b = max(0, min(255, $b * 0.8));
            
            return sprintf('#%02x%02x%02x', $r, $g, $b);
        }
        
        // Default darker shade for non-hex colors
        return '#0d9488';
    }
}

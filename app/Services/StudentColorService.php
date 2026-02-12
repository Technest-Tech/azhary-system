<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentColorService
{
    /**
     * Predefined color palette - maximally distinct colors (no similar hues)
     */
    private static $colorPalette = [
        '#e53935', // Red
        '#ff6d00', // Orange
        '#f9a825', // Amber / Yellow
        '#7cb342', // Lime green
        '#00897b', // Teal
        '#00acc1', // Cyan
        '#1e88e5', // Blue
        '#5e35b1', // Deep purple / Indigo
        '#8e24aa', // Violet
        '#d81b60', // Magenta / Pink
        '#c62828', // Dark red
        '#ef6c00', // Deep orange
        '#fbc02d', // Gold
        '#43a047', // Green
        '#00695c', // Dark teal
        '#0097a7', // Dark cyan
        '#1565c0', // Navy blue
        '#4527a0', // Purple
        '#6a1b9a', // Deep violet
        '#ad1457', // Rose
        '#b71c1c', // Crimson
        '#e65100', // Burnt orange
        '#f57f17', // Dark amber
        '#2e7d32', // Forest green
        '#004d40', // Dark green
        '#006064', // Dark cyan-green
        '#0d47a1', // Royal blue
        '#311b92', // Indigo
        '#4a148c', // Deep purple
        '#880e4f', // Dark pink
    ];

    /**
     * Generate a unique color for a student based on their teacher
     * Ensures no two students of the same teacher have the same color
     * 
     * @param int $teacherId The teacher ID
     * @param int|null $excludeStudentId Optional student ID to exclude from duplicate check
     */
    public static function generateUniqueColorForTeacher($teacherId, $excludeStudentId = null): string
    {
        if (!$teacherId) {
            // If no teacher, return a default color
            return self::$colorPalette[0];
        }

        // Get all colors already assigned to students of this teacher
        $query = Student::where('teacher_id', $teacherId)
            ->whereNotNull('color');
        
        // Exclude current student if provided
        if ($excludeStudentId) {
            $query->where('id', '!=', $excludeStudentId);
        }
        
        $usedColors = $query->pluck('color')
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
     * Force refresh colors for all students (clear and reassign from palette)
     */
    public static function refreshAllStudentsColors(): array
    {
        Student::whereNotNull('teacher_id')->update(['color' => null]);
        return self::assignColorsToAllStudents();
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

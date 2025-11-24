<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RecurringCourse extends Model
{
    protected $fillable = [
        'teacher_id',
        'student_id',
        'student_name',
        'course_type',
        'class_time',
        'day_of_week',
        'duration_hours',
        'duration_minutes',
        'start_date',
        'end_date',
        'recurrence_type',
        'recurrence_value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'class_time' => 'datetime:H:i',
            'is_active' => 'boolean',
            'duration_hours' => 'integer',
            'duration_minutes' => 'integer',
            'recurrence_value' => 'integer',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'recurring_course_id');
    }

    /**
     * Generate Course instances based on recurrence pattern
     */
    public function generateInstances($untilDate = null)
    {
        $instances = [];
        $startDate = Carbon::parse($this->start_date);
        $endDate = $untilDate ? Carbon::parse($untilDate) : ($this->end_date ? Carbon::parse($this->end_date) : null);
        
        $dayMap = [
            'Monday' => Carbon::MONDAY,
            'Tuesday' => Carbon::TUESDAY,
            'Wednesday' => Carbon::WEDNESDAY,
            'Thursday' => Carbon::THURSDAY,
            'Friday' => Carbon::FRIDAY,
            'Saturday' => Carbon::SATURDAY,
            'Sunday' => Carbon::SUNDAY,
        ];
        
        $targetDay = $dayMap[$this->day_of_week];
        
        // Calculate how many instances to generate
        $maxInstances = null;
        
        switch ($this->recurrence_type) {
            case 'weeks_count':
                $maxInstances = $this->recurrence_value ?? 52; // Default to 1 year if not set
                break;
            case 'months_count':
                $maxInstances = ($this->recurrence_value ?? 12) * 4; // Approximate weeks per month
                break;
            case 'endless':
                // Generate up to 1 year ahead or until end_date if set
                $maxInstances = $endDate ? null : 52;
                break;
            case 'weekly':
            default:
                // Generate until end_date if set, otherwise up to 1 year
                $maxInstances = $endDate ? null : 52;
                break;
        }
        
        // Find the first occurrence of the target day on or after start_date
        $currentDate = $startDate->copy();
        if ($currentDate->dayOfWeek !== $targetDay) {
            $currentDate->next($targetDay);
        }
        
        $instanceCount = 0;
        
        while (true) {
            // Check if we've exceeded our limit
            if ($maxInstances !== null && $instanceCount >= $maxInstances) {
                break;
            }
            
            // Check if we've passed the end date
            if ($endDate && $currentDate->gt($endDate)) {
                break;
            }
            
            // Check if we should limit to a reasonable future (e.g., 1 year)
            if (!$endDate && $currentDate->gt($startDate->copy()->addYear())) {
                break;
            }
            
            // Create course instance for this date
            $courseDate = $currentDate->copy();
            
            // Check if course already exists for this date/time
            $existingCourse = Course::where('teacher_id', $this->teacher_id)
                ->where('student_id', $this->student_id)
                ->whereDate('course_date', $courseDate->format('Y-m-d'))
                ->whereTime('class_time', $this->class_time)
                ->where('recurring_course_id', $this->id)
                ->first();
            
            if (!$existingCourse) {
                $instances[] = [
                    'teacher_id' => $this->teacher_id,
                    'student_id' => $this->student_id,
                    'student_name' => $this->student_name ?? $this->student->name,
                    'name' => $this->course_type . ' - Recurring',
                    'course_date' => $courseDate->format('Y-m-d'),
                    'class_time' => $this->class_time,
                    'duration_hours' => $this->duration_hours,
                    'duration_minutes' => $this->duration_minutes,
                    'course_type' => $this->course_type,
                    'status' => 'Pending',
                    'is_recurring' => true,
                    'recurring_course_id' => $this->id,
                ];
            }
            
            // Move to next week
            $currentDate->addWeek();
            $instanceCount++;
        }
        
        // Bulk insert courses
        if (!empty($instances)) {
            foreach ($instances as $instanceData) {
                // Calculate n_value and income for each instance
                $student = Student::find($instanceData['student_id']);
                $teacher = Teacher::find($instanceData['teacher_id']);
                
                if ($student && $teacher) {
                    $previousCourse = Course::where('student_id', $student->id)
                        ->where('teacher_id', $teacher->id)
                        ->orderBy('n_value', 'desc')
                        ->first();
                    
                    $previousNValue = $previousCourse ? $previousCourse->n_value : 0;
                    $currentDuration = $instanceData['duration_hours'] + ($instanceData['duration_minutes'] / 60.0);
                    $instanceData['n_value'] = $previousNValue + $currentDuration;
                    $instanceData['total_hours'] = $currentDuration;
                    $instanceData['income'] = $currentDuration * $teacher->hourly_rate;
                    
                    Course::create($instanceData);
                    
                    // Update n_value for subsequent courses
                    $this->updateNValues($student->id, $teacher->id);
                }
            }
        }
        
        return count($instances);
    }
    
    /**
     * Update n_values for all courses of a student after inserting new ones
     */
    private function updateNValues($studentId, $teacherId)
    {
        $courses = Course::where('student_id', $studentId)
            ->where('teacher_id', $teacherId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $cumulativeValue = 0;
        
        foreach ($courses as $course) {
            $duration = $course->duration_hours + ($course->duration_minutes / 60.0);
            $cumulativeValue += $duration;
            
            if ($course->n_value != $cumulativeValue) {
                $course->update(['n_value' => $cumulativeValue]);
            }
        }
    }
}

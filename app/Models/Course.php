<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'teacher_id',
        'student_id',
        'round',
        'student_name',
        'n_value',
        'class_time',
        'course_type',
        'course_date',
        'duration_hours',
        'duration_minutes',
        'status',
        'admin_status',
        'homework',
        'evaluation_id',
        'content',
        'notes',
        'souvenir_image',
        'name',
        'total_hours',
        'income',
        'is_recurring',
        'recurring_course_id',
    ];

    protected function casts(): array
    {
        return [
            'total_hours' => 'decimal:2',
            'income' => 'decimal:2',
            'course_date' => 'date',
            'class_time' => 'datetime:H:i',
            'n_value' => 'decimal:2',
            'duration_hours' => 'integer',
            'duration_minutes' => 'integer',
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

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function recurringCourse()
    {
        return $this->belongsTo(RecurringCourse::class, 'recurring_course_id');
    }
}

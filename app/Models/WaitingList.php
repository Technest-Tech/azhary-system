<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    protected $table = 'waiting_list';

    protected $fillable = [
        'teacher_id',
        'student_id',
        'student_name',
        'name',
        'course_date',
        'class_time',
        'duration_hours',
        'duration_minutes',
        'total_hours',
        'course_type',
        'status',
        'admin_status',
        'homework',
        'evaluation_id',
        'content',
        'notes',
        'souvenir_image',
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
            'duration_hours' => 'integer',
            'duration_minutes' => 'integer',
            'is_recurring' => 'boolean',
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

    /**
     * Convert waiting list entry to a Course
     */
    public function toCourse($nValue = 0)
    {
        return [
            'teacher_id' => $this->teacher_id,
            'student_id' => $this->student_id,
            'student_name' => $this->student_name,
            'name' => $this->name,
            'course_date' => $this->course_date,
            'class_time' => $this->class_time,
            'duration_hours' => $this->duration_hours,
            'duration_minutes' => $this->duration_minutes,
            'total_hours' => $this->total_hours,
            'course_type' => $this->course_type,
            'status' => $this->status,
            'admin_status' => $this->admin_status,
            'homework' => $this->homework,
            'evaluation_id' => $this->evaluation_id,
            'content' => $this->content,
            'notes' => $this->notes,
            'souvenir_image' => $this->souvenir_image,
            'income' => $this->income,
            'n_value' => $nValue,
            'is_recurring' => $this->is_recurring,
            'recurring_course_id' => $this->recurring_course_id,
        ];
    }
}

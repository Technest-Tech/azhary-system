<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

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
        'is_manual_n_value',
        'package_limit',
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
            'is_manual_n_value' => 'boolean',
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

    public function deletedBy()
    {
        return $this->morphTo('deleted_by', 'deleted_by_type', 'deleted_by_id');
    }

    /**
     * Get payment status badge information for this course's round
     * Returns array with 'label' and 'color' for display
     */
    public function getPaymentStatusBadge()
    {
        // Round 0 = pending courses, always "Not Paid"
        if ($this->round == 0) {
            return [
                'label' => 'Not Paid',
                'color' => '#ef4444', // red
                'bg_color' => '#fee2e2',
                'text_color' => '#991b1b'
            ];
        }

        // Get the current active round for this student (max round > 0)
        $currentActiveRound = Course::where('student_id', $this->student_id)
            ->where('round', '>', 0)
            ->max('round') ?? 1;

        // If this is the current active round, use student's current payment status
        if ($this->round == $currentActiveRound) {
            $paymentStatus = $this->student->paymentStatus;
            
            if (!$paymentStatus) {
                return [
                    'label' => 'Not Paid',
                    'color' => '#ef4444',
                    'bg_color' => '#fee2e2',
                    'text_color' => '#991b1b'
                ];
            }

            $statusName = $paymentStatus->name;
            
            if ($statusName === 'PAYÉ' || $statusName === 'Active') {
                return [
                    'label' => 'Paid',
                    'color' => '#10b981', // green
                    'bg_color' => '#d1fae5',
                    'text_color' => '#065f46'
                ];
            } elseif ($statusName === 'EN ATTENTE DE PAYEMENT') {
                return [
                    'label' => 'Not Paid',
                    'color' => '#ef4444',
                    'bg_color' => '#fee2e2',
                    'text_color' => '#991b1b'
                ];
            } else {
                // For other statuses like ARRÊTÉ, show as "Active" or "Not Paid" based on context
                return [
                    'label' => $statusName === 'ARRÊTÉ' ? 'Stopped' : 'Active',
                    'color' => '#f59e0b', // amber
                    'bg_color' => '#fef3c7',
                    'text_color' => '#92400e'
                ];
            }
        } else {
            // Previous completed round - it was paid (otherwise it wouldn't have been completed)
            return [
                'label' => 'Paid',
                'color' => '#10b981',
                'bg_color' => '#d1fae5',
                'text_color' => '#065f46'
            ];
        }
    }
}

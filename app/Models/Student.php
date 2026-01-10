<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class Student extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'photo',
        'password',
        'section',
        'package_number',
        'hour_rate',
        'package_rate',
        'payment_status_id',
        'teacher_id',
        'teacher_rate',
        'subject_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'hour_rate' => 'decimal:2',
            'package_rate' => 'decimal:2',
            'teacher_rate' => 'decimal:2',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    public function waitingList()
    {
        return $this->hasMany(WaitingList::class);
    }

    /**
     * Get the activity status based on last course attendance
     * Active: Last course within 30 days
     * Inactive: Last course 31-60 days ago
     * Suspended: Last course >60 days ago
     * Archived: Payment status is "ARRÊTÉ"
     */
    public function getActivityStatus()
    {
        // Check if archived (payment status is ARRÊTÉ)
        if ($this->paymentStatus && $this->paymentStatus->name === 'ARRÊTÉ') {
            return 'Archived';
        }

        $lastCourse = $this->courses()
            ->where('status', 'Present')
            ->whereNotIn('name', ['0', '0.0'])
            ->orderBy('course_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->first();

        if (!$lastCourse) {
            return 'Suspended'; // No courses at all
        }

        $daysSinceLastCourse = Carbon::now()->diffInDays($lastCourse->course_date);

        if ($daysSinceLastCourse <= 30) {
            return 'Active';
        } elseif ($daysSinceLastCourse <= 60) {
            return 'Inactive';
        } else {
            // More than 2 months (60 days) = Suspended
            return 'Suspended';
        }
    }

    /**
     * Get the last activity date
     */
    public function getLastActivityDate()
    {
        $lastCourse = $this->courses()
            ->where('status', 'Present')
            ->whereNotIn('name', ['0', '0.0'])
            ->orderBy('course_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->first();

        return $lastCourse ? $lastCourse->course_date : null;
    }

    /**
     * Get the number of courses taken (Present courses with valid names)
     */
    public function getCoursesTakenCount()
    {
        return $this->courses()
            ->where('status', 'Present')
            ->whereNotIn('name', ['0', '0.0'])
            ->count();
    }

    /**
     * Get remaining lessons in package
     */
    public function getRemainingLessons()
    {
        $coursesTaken = $this->getCoursesTakenCount();
        $remaining = $this->package_number - $coursesTaken;
        return max(0, $remaining);
    }

    /**
     * Get remaining money (unpaid amount for courses beyond package limit)
     */
    public function getRemainingMoney()
    {
        $coursesBeyondLimit = $this->courses()
            ->where('name', '0.0')
            ->sum('income');

        return $coursesBeyondLimit ?? 0;
    }

    /**
     * Get total fare (sum of course income within package)
     */
    public function getFare()
    {
        $coursesWithinPackage = $this->courses()
            ->where('status', 'Present')
            ->whereNotIn('name', ['0', '0.0'])
            ->sum('income');

        return $coursesWithinPackage ?? 0;
    }

    /**
     * Get teacher profit (total income from student's courses)
     */
    public function getTeacherProfit()
    {
        $totalIncome = $this->courses()
            ->where('status', 'Present')
            ->whereNotIn('name', ['0', '0.0'])
            ->sum('income');

        return $totalIncome ?? 0;
    }

    /**
     * Get price of a pack (package_rate)
     */
    public function getPriceOfPack()
    {
        return $this->package_rate ?? 0;
    }
}

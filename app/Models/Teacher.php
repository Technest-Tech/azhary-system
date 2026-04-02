<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'photo',
        'password',
        'currency',
        'hourly_rate',
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
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function deletedBy()
    {
        return $this->morphTo('deleted_by', 'deleted_by_type', 'deleted_by_id');
    }
}

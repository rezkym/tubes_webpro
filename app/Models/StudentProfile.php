<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    /** @use HasFactory<\Database\Factories\StudentProfileFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_class_id',
        'student_number',
        'full_name',
        'date_of_birth',
        'gender',
        'address',
        'parent_name',
        'parent_phone',
        'enrollment_date'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_profile_id',
        'school_class_id',
        'school_subject_id',
        'teacher_profile_id',
        'date',
        'status',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_SICK = 'sick';
    const STATUS_PERMITTED = 'permitted';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',
            self::STATUS_LATE => 'Late',
            self::STATUS_SICK => 'Sick',
            self::STATUS_PERMITTED => 'Permitted'
        ];
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_profile_id');
    }

    public function subject()
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
}
